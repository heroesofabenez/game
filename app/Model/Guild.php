<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Request as RequestEntity,
    HeroesofAbenez\Orm\Guild as GuildEntity,
    HeroesofAbenez\Orm\GuildDummy,
    Nette\Utils\Arrays,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\GuildRankCustom,
    Nextras\Orm\Collection\ICollection;

  /**
   * Model Guild
   * 
   * @author Jakub Konečný
   */
class Guild {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\Model\Profile */
  protected $profileModel;
  /** @var \HeroesofAbenez\Model\Permissions */
  protected $permissionsModel;
  
  function __construct(ORM $orm, \Nette\Caching\Cache $cache, \Nette\Security\User $user, Profile $profileModel, Permissions $permissionsModel) {
    $this->cache = $cache;
    $this->orm = $orm;
    $this->user = $user;
    $this->profileModel = $profileModel;
    $this->permissionsModel = $permissionsModel;
  }
  
  /**
   * Get name of specified guild
   * 
   * @param int $id Id of guild
   * @return string
   */
  function getGuildName(int $id): string {
    $guild = Arrays::get($this->listOfGuilds(), $id, NULL);
    if(is_null($guild)) {
      return "";
    } else {
      return $guild->name;
    }
  }
  
  /**
   * Get data about specified guild
   * 
   * @param int $id Id of guild
   * @return GuildDummy
   */
  function guildData(int $id) {
    $guilds = $this->listOfGuilds();
    return $guilds[$id];
  }
  
  /**
   * Gets basic data about specified guild
   * 
   * @param integer $id guild's id
   * @return GuildEntity|NULL
   */
  function view(int $id): ?GuildEntity {
    return $this->orm->guilds->getById($id);
  }
  
  /**
   * Get a guild's custom name for a rank
   * 
   * @param int $guild
   * @param int $rank
   * @return string
   */
  function getCustomRankName(int $guild, int $rank): string {
    $customRank = $this->orm->guildRanksCustom->getByGuildAndRank($guild, $rank);
    if(is_null($customRank)) {
      return "";
    } else {
      return $customRank->name;
    }
  }
  
  /**
   * Get members of specified guild
   * 
   * @param int $id Id of guild
   * @param array $roles Return only members with these roles
   * @param bool $customRoleNames Whetever the guild's custom names should be used
   * @return \stdClass[]
   */
  function guildMembers(int $id, array $roles = [], bool $customRoleNames = false): array {
    $return = [];
    $members = $this->orm->characters->findByGuild($id);
    if(count($roles)) {
      $members = $members->findBy(["guildrank" => $roles]);
    }
    foreach($members as $member) {
      $rank = $member->guildrank;
      $m = (object) ["id" => $member->id, "name" => $member->name, "rank" => $rank, "rankId" => $member->guildrank->id, "customRankName" => ""];
      if($customRoleNames) {
        $m->customRankName = $this->getCustomRankName($id, $m->rankId);
      }
      $return[] = $m;
    }
    return $return;
  }
  
  /**
   * Creates a guild
   * 
   * @param array $data Name and description
   * @return void
   * @throws NameInUseException
   */
  function create($data): void {
    $guild = $this->orm->guilds->getByName($data["name"]);
    if(!is_null($guild)) {
      throw new NameInUseException;
    }
    $guild = new \HeroesofAbenez\Orm\Guild;
    foreach($data as $key => $value) {
      $guild->$key = $value;
    }
    $character = $this->orm->characters->getById($this->user->id);
    $character->guild = $guild;
    $character->guildrank = 7;
    $this->orm->characters->persistAndFlush($character);
    $this->cache->remove("guilds");
  }
  
  /**
   * Send application to a guild
   * 
   * @param int $gid Guild to join
   * @return void
   * @throws GuildNotFoundException
   */
  function sendApplication(int $gid): void {
    $guild = $this->orm->guilds->getById($gid);
    if(is_null($guild)) {
      throw new GuildNotFoundException;
    }
    $leader = $this->orm->characters->getBy([
      "guild" => $gid, "guildrank" => 7,
    ]);
    $request = new RequestEntity;
    $this->orm->requests->attach($request);
    $request->from = $this->user->id;
    $request->to = $leader;
    $request->type = RequestEntity::TYPE_GUILD_APP;
    $this->orm->requests->persistAndFlush($request);
  }
  
  /**
   * Check if player has an unresolved application
   * 
   * @return bool
   */
  function haveUnresolvedApplication(): bool {
    $app = $this->orm->requests->getBy([
      "from" => $this->user->id,
      "type" => RequestEntity::TYPE_GUILD_APP,
      "status" => RequestEntity::STATUS_NEW,
    ]);
    return (!is_null($app));
  }
  
  /**
   * Get unresolved applications to specified guild
   * 
   * @param int $id Guild's id
   * @return ICollection|RequestEntity[]
   */
  function showApplications(int $id): ICollection {
    $guilds = $this->listOfGuilds();
    $guild = $guilds[$id];
    $leaderId = $this->profileModel->getCharacterId($guild->leader);
    return $this->orm->requests->findUnresolvedGuildApplications($leaderId);
  }
  
  /**
   * Gets list of guilds
   *
   * @return GuildDummy[] list of guilds (id, name, description, leader)
   */
  function listOfGuilds(): array {
    $guilds = $this->cache->load("guilds", function(& $dependencies) {
      $guilds = [];
      $rows = $this->orm->guilds->findBy(["id>" => 0]);
      /** @var GuildEntity $guild */
      foreach($rows as $guild) {
        $leader = "";
        foreach($guild->members as $member) {
          if($member->guildrank->id == 7) {
            $leader = $member->name;
          }
        }
        $guilds[$guild->id] = new GuildDummy($guild->id, $guild->name, $guild->description, $guild->members->countStored(), $leader);
      }
      return $guilds;
    });
    return $guilds;
  }
  
  /**
   * Increase rank of specified member of guild
   * 
   * @param int $id Id of player to be demoted
   * @return void
   * @throws NotInGuildException
   * @throws MissingPermissionsException
   * @throws PlayerNotFoundException
   * @throws PlayerNotInGuildException
   * @throws CannotPromoteHigherRanksException
   * @throws CannotPromoteToGrandmasterException
   * @throws CannotHaveMoreDeputiesException
   */
  function promote(int $id): void {
    $admin = $this->user;
    if($admin->identity->guild == 0) {
      throw new NotInGuildException;
    }
    if(!$admin->isAllowed("guild", "promote")) {
      throw new MissingPermissionsException;
    }
    $character = $this->orm->characters->getById($id);
    if(is_null($character)) {
      throw new PlayerNotFoundException;
    }
    if($character->guild->id !== $admin->identity->guild) {
      throw new PlayerNotInGuildException;
    }
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank->id) {
      throw new CannotPromoteHigherRanksException;
    }
    if($character->guildrank->id >= 6) {
      throw new CannotPromoteToGrandmasterException;
    }
    if($character->guildrank->id == 5) {
      $deputy = $this->guildMembers($admin->identity->guild, [6]);
      if(count($deputy) > 0) {
        throw new CannotHaveMoreDeputiesException;
      }
    }
    $character->guildrank = $character->guildrank->id + 1;
    $this->orm->characters->persistAndFlush($character);
  }
  
  /**
   * Decrease rank of specified member of guild
   * 
   * @param int $id Id of player to be demoted
   * @return void
   * @throws NotInGuildException
   * @throws MissingPermissionsException
   * @throws PlayerNotFoundException
   * @throws PlayerNotInGuildException
   * @throws CannotDemoteHigherRanksException
   * @throws CannotDemoteLowestRankException
   */
  function demote(int $id): void {
    $admin = $this->user;
    if($admin->identity->guild == 0) {
      throw new NotInGuildException;
    }
    if(!$admin->isAllowed("guild", "promote")) {
      throw new MissingPermissionsException;
    }
    $character = $this->orm->characters->getById($id);
    if(is_null($character)) {
      throw new PlayerNotFoundException;
    }
    if($character->guild->id !== $admin->identity->guild) {
      throw new PlayerNotInGuildException;
    }
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank->id) {
      throw new CannotDemoteHigherRanksException;
    }
    if($character->guildrank->id === 1) {
      throw new CannotDemoteLowestRankException;
    }
    $character->guildrank = $character->guildrank->id - 1;
    $this->orm->characters->persistAndFlush($character);
  }
  
  /**
   * Kick specified member from guild
   * 
   * @param int $id Id of player to be kicked
   * @return void
   * @throws NotInGuildException
   * @throws MissingPermissionsException
   * @throws PlayerNotFoundException
   * @throws PlayerNotInGuildException
   * @throws CannotKickHigherRanksException
   */
  function kick(int $id): void {
    $admin = $this->user;
    if($admin->identity->guild == 0) {
      throw new NotInGuildException;
    }
    if(!$admin->isAllowed("guild", "kick")) {
      throw new MissingPermissionsException;
    }
    $character = $this->orm->characters->getById($id);
    if(is_null($character)) {
      throw new PlayerNotFoundException;
    }
    if($character->guild->id !== $admin->identity->guild) {
      throw new PlayerNotInGuildException;
    }
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank->id) {
      throw new CannotKickHigherRanksException;
    }
    $character->guildrank = NULL;
    $character->guild = 0;
    $this->orm->characters->persistAndFlush($character);
    $this->cache->remove("guilds");
  }
  
  /**
   * Leave the guild
   * 
   * @return void
   * @throws NotInGuildException
   * @throws GrandmasterCannotLeaveGuildException
  */
  function leave(): void {
    if($this->user->identity->guild === 0) {
      throw new NotInGuildException;
    }
    if($this->user->isInRole("grandmaster")) {
      throw new GrandmasterCannotLeaveGuildException;
    }
    $character = $this->orm->characters->getById($this->user->id);
    $character->guildrank = NULL;
    $character->guild = 0;
    $this->orm->characters->persistAndFlush($character);
    $this->cache->remove("guilds");
  }
  
  /**
   * Dissolve guild
   *
   * @param int $id Guild to dissolve
   * @return void
   */
  function dissolve(int $id): void {
    $guild = $this->orm->guilds->getById($id);
    foreach($guild->members as $member) {
      $member->guild = 0;
      $member->guildrank = NULL;
      $this->orm->characters->persist($member);
    }
    $guild = $this->orm->guilds->getById($id);
    $this->orm->guilds->remove($guild);
    $this->orm->flush();
    $this->cache->remove("guilds");
  }
  
  /**
   * Rename guild
   *
   * @param int $id Guild to rename
   * @param string $name New name
   * @return void
   * @throws NameInUseException
  */
  function rename(int $id, string $name): void {
    $guild = $this->orm->guilds->getByName($name);
    if(!is_null($guild) AND $guild->id !== $id) {
      throw new NameInUseException;
    }
    $guild = $this->orm->guilds->getById($id);
    $guild->name = $name;
    $this->orm->guilds->persistAndFlush($guild);
    $this->cache->remove("guilds");
  }
  
  /**
   * Change description of specified guild
   * 
   * @param int $id Guild's id
   * @param string $description New description
   * @return void
   * @throws GuildNotFoundException
   */
  function changeDescription(int $id, string $description): void {
    $guild = $this->orm->guilds->getById($id);
    if(is_null($guild)) {
      throw new GuildNotFoundException;
    }
    $guild->description = $description;
    $this->orm->guilds->persistAndFlush($guild);
    $this->cache->remove("guilds");
  }
  
  /**
   * Join a guild
   * 
   * @param int $uid Character's id
   * @param int $gid Guild's id
   * @return void
   */
  function join(int $uid, int $gid): void {
    $character = $this->orm->characters->getById($uid);
    $character->guild = $gid;
    $character->guildrank = 1;
    $this->orm->characters->persistAndFlush($character);
    $this->cache->remove("guilds");
  }
  
  /**
   * Get default rank names
   *
   * @return string[]
   */
  function getDefaultRankNames() {
    return $this->orm->guildRanks->findAll()->fetchPairs("id", "name");
  }
  
  /**
   * Get custom rank names for a guild
   *
   * @param int $id
   * @return string[]
   */
  function getCustomRankNames(int $id): array {
    return $this->orm->guildRanksCustom->findByGuild($id)->fetchPairs("rank", "name");
  }
  
  /**
   * Set custom rank names for user's guild
   *
   * @param array $names
   * @return void
   * @throws MissingPermissionsException
   */
  function setCustomRankNames(array $names): void {
    if(!$this->user->isAllowed("guild", "changeRankNames")) {
      throw new MissingPermissionsException;
    }
    $gid = $this->user->identity->guild;
    foreach($names as $rank => $name) {
      if($name === "") {
        continue;
      }
      $rank = substr($rank, 4, 1);
      $row = $this->orm->guildRanksCustom->getByGuildAndRank($gid, $rank);
      if(is_null($row)) {
        $row = new GuildRankCustom;
        $this->orm->guildRanksCustom->attach($row);
        $row->guild = $gid;
        $row->rank = $rank;
        $row->name = $name;
      } else {
        $row->name = $name;
      }
      $this->orm->guildRanksCustom->persistAndFlush($row);
    }
  }
}
?>