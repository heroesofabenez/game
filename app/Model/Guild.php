<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Character as CharacterEntity;
use HeroesofAbenez\Orm\GuildDonation;
use HeroesofAbenez\Orm\GuildRank;
use HeroesofAbenez\Orm\Request as RequestEntity;
use HeroesofAbenez\Orm\Guild as GuildEntity;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\GuildRankCustom;
use Nextras\Orm\Collection\ICollection;

/**
 * Model Guild
 *
 * @author Jakub Konečný
 */
final class Guild {
  public function __construct(private readonly ORM $orm, private readonly \Nette\Security\User $user, private readonly Permissions $permissionsModel) {
  }
  
  /**
   * Get name of specified guild
   */
  public function getGuildName(int $id): string {
    $guild = $this->view($id);
    if($guild === null) {
      return "";
    }
    return $guild->name;
  }
  
  /**
   * Gets basic data about specified guild
   */
  public function view(int $id): ?GuildEntity {
    return $this->orm->guilds->getById($id);
  }
  
  /**
   * Get a guild's custom name for a rank
   */
  public function getCustomRankName(int $guild, int $rank): string {
    $customRank = $this->orm->guildRanksCustom->getByGuildAndRank($guild, $rank);
    if($customRank === null) {
      return "";
    }
    return $customRank->name;
  }
  
  /**
   * Get members of specified guild
   * 
   * @param int $id Id of guild
   * @param int[] $roles Return only members with these roles
   * @param bool $customRoleNames Whether the guild's custom names should be used
   * @return \stdClass[]
   */
  public function guildMembers(int $id, array $roles = [], bool $customRoleNames = false): array {
    $return = [];
    $members = $this->orm->characters->findByGuild($id);
    if(count($roles) > 0) {
      $members = $members->findBy(["guildrank" => $roles]);
    }
    foreach($members as $member) {
      /** @var GuildRank $rank */
      $rank = $member->guildrank;
      $m = (object) [
        "id" => $member->id, "name" => $member->name, "rank" => $rank, "rankId" => $rank->id,
        "customRankName" => "", "currentGuildContribution" => $member->currentGuildContribution,
      ];
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
   * @throws NameInUseException
   */
  public function create(array $data): void {
    $guild = $this->orm->guilds->getByName($data["name"]);
    if($guild !== null) {
      throw new NameInUseException();
    }
    $guild = new GuildEntity();
    foreach($data as $key => $value) {
      $guild->$key = $value;
    }
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $character->guild = $guild;
    $character->guildrank = 7;
    $this->orm->characters->persistAndFlush($character);
  }
  
  /**
   * Send application to a guild
   *
   * @throws GuildNotFoundException
   */
  public function sendApplication(int $gid): void {
    $guild = $this->orm->guilds->getById($gid);
    if($guild === null) {
      throw new GuildNotFoundException();
    }
    $request = new RequestEntity();
    $this->orm->requests->attach($request);
    $request->from = $this->user->id;
    /** @var CharacterEntity $leader */
    $leader = $guild->leader;
    $request->to = $leader;
    $request->type = RequestEntity::TYPE_GUILD_APP;
    $this->orm->requests->persistAndFlush($request);
  }
  
  /**
   * Check if player has an unresolved application
   */
  public function haveUnresolvedApplication(): bool {
    $app = $this->orm->requests->getBy([
      "from" => $this->user->id,
      "type" => RequestEntity::TYPE_GUILD_APP,
      "status" => RequestEntity::STATUS_NEW,
    ]);
    return ($app !== null);
  }
  
  /**
   * Get unresolved applications to specified guild
   * 
   * @param int $id Guild's id
   * @return ICollection|RequestEntity[]
   */
  public function showApplications(int $id): ICollection {
    /** @var GuildEntity $guild */
    $guild = $this->view($id);
    /** @var CharacterEntity $leader */
    $leader = $guild->leader;
    return $this->orm->requests->findUnresolvedGuildApplications($leader->id);
  }
  
  /**
   * Gets list of guilds
   *
   * @return ICollection|GuildEntity[] list of guilds (id, name, description, leader)
   */
  public function listOfGuilds(): ICollection {
    return $this->orm->guilds->findAll();
  }
  
  /**
   * Increase rank of specified member of guild
   *
   * @throws NotInGuildException
   * @throws MissingPermissionsException
   * @throws PlayerNotFoundException
   * @throws PlayerNotInGuildException
   * @throws CannotPromoteHigherRanksException
   * @throws CannotPromoteToGrandmasterException
   * @throws CannotHaveMoreDeputiesException
   */
  public function promote(int $id): void {
    $admin = $this->user;
    if($admin->identity->guild === 0) {
      throw new NotInGuildException();
    }
    if(!$admin->isAllowed("guild", "promote")) {
      throw new MissingPermissionsException();
    }
    $character = $this->orm->characters->getById($id);
    if($character === null) {
      throw new PlayerNotFoundException();
    }
    if($character->guild === null || $character->guildrank === null) {
      throw new PlayerNotInGuildException();
    }
    if($character->guild->id !== $admin->identity->guild) {
      throw new PlayerNotInGuildException();
    }
    $adminRole = $this->permissionsModel->getRankId($admin->roles[0]);
    if($adminRole <= $character->guildrank->id) {
      throw new CannotPromoteHigherRanksException();
    }
    if($character->guildrank->id >= 6) {
      throw new CannotPromoteToGrandmasterException();
    }
    if($character->guildrank->id === 5) {
      $deputy = $this->guildMembers($admin->identity->guild, [6]);
      if(count($deputy) > 0) {
        throw new CannotHaveMoreDeputiesException();
      }
    }
    $character->guildrank = $character->guildrank->id + 1;
    $this->orm->characters->persistAndFlush($character);
  }
  
  /**
   * Decrease rank of specified member of guild
   *
   * @throws NotInGuildException
   * @throws MissingPermissionsException
   * @throws PlayerNotFoundException
   * @throws PlayerNotInGuildException
   * @throws CannotDemoteHigherRanksException
   * @throws CannotDemoteLowestRankException
   */
  public function demote(int $id): void {
    $admin = $this->user;
    if($admin->identity->guild === 0) {
      throw new NotInGuildException();
    }
    if(!$admin->isAllowed("guild", "promote")) {
      throw new MissingPermissionsException();
    }
    $character = $this->orm->characters->getById($id);
    if($character === null) {
      throw new PlayerNotFoundException();
    }
    if($character->guild === null) {
      throw new PlayerNotInGuildException();
    }
    if($character->guild->id !== $admin->identity->guild || $character->guildrank === null) {
      throw new PlayerNotInGuildException();
    }
    $adminRole = $this->permissionsModel->getRankId($admin->roles[0]);
    if($adminRole <= $character->guildrank->id) {
      throw new CannotDemoteHigherRanksException();
    }
    if($character->guildrank->id === 1) {
      throw new CannotDemoteLowestRankException();
    }
    $character->guildrank = $character->guildrank->id - 1;
    $this->orm->characters->persistAndFlush($character);
  }
  
  /**
   * Kick specified member from guild
   *
   * @throws NotInGuildException
   * @throws MissingPermissionsException
   * @throws PlayerNotFoundException
   * @throws PlayerNotInGuildException
   * @throws CannotKickHigherRanksException
   */
  public function kick(int $id): void {
    $admin = $this->user;
    if($admin->identity->guild === 0) {
      throw new NotInGuildException();
    }
    if(!$admin->isAllowed("guild", "kick")) {
      throw new MissingPermissionsException();
    }
    $character = $this->orm->characters->getById($id);
    if($character === null) {
      throw new PlayerNotFoundException();
    }
    if($character->guild === null || $character->guild->id !== $admin->identity->guild || $character->guildrank === null) {
      throw new PlayerNotInGuildException();
    }
    $adminRole = $this->permissionsModel->getRankId($admin->roles[0]);
    if($adminRole <= $character->guildrank->id) {
      throw new CannotKickHigherRanksException();
    }
    $character->guild = $character->guildrank = null;
    $this->orm->characters->persistAndFlush($character);
  }

  /**
   * Leave the guild
   *
   * @throws NotInGuildException
   * @throws GrandmasterCannotLeaveGuildException
   */
  public function leave(): void {
    if($this->user->identity->guild === 0) {
      throw new NotInGuildException();
    }
    if($this->user->isInRole("grandmaster")) {
      throw new GrandmasterCannotLeaveGuildException();
    }
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $character->guild = $character->guildrank = null;
    $this->orm->characters->persistAndFlush($character);
  }
  
  /**
   * Dissolve guild
   */
  public function dissolve(int $id): void {
    /** @var GuildEntity $guild */
    $guild = $this->orm->guilds->getById($id);
    foreach($guild->members as $member) {
      $member->guild = $member->guildrank = null;
      $this->orm->characters->persist($member);
    }
    /** @var GuildEntity $guild */
    $guild = $this->orm->guilds->getById($id);
    $this->orm->guilds->remove($guild);
    $this->orm->flush();
  }

  /**
   * Rename guild
   *
   * @throws NameInUseException
   */
  public function rename(int $id, string $name): void {
    $guild = $this->orm->guilds->getByName($name);
    if($guild !== null && $guild->id !== $id) {
      throw new NameInUseException();
    }
    /** @var GuildEntity $guild */
    $guild = $this->orm->guilds->getById($id);
    $guild->name = $name;
    $this->orm->guilds->persistAndFlush($guild);
  }
  
  /**
   * Change description of specified guild
   *
   * @throws GuildNotFoundException
   */
  public function changeDescription(int $id, string $description): void {
    $guild = $this->orm->guilds->getById($id);
    if($guild === null) {
      throw new GuildNotFoundException();
    }
    $guild->description = $description;
    $this->orm->guilds->persistAndFlush($guild);
  }
  
  /**
   * Join a guild
   */
  public function join(int $uid, int $gid): void {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($uid);
    $character->guild = $gid;
    $character->guildrank = 1;
    $this->orm->characters->persistAndFlush($character);
  }
  
  /**
   * Get default rank names
   *
   * @return string[]
   */
  public function getDefaultRankNames() {
    return $this->orm->guildRanks->findAll()->fetchPairs("id", "name");
  }
  
  /**
   * Get custom rank names for a guild
   *
   * @return string[]
   */
  public function getCustomRankNames(int $id): array {
    return $this->orm->guildRanksCustom->findByGuild($id)->fetchPairs("rank->id", "name");
  }
  
  /**
   * Set custom rank names for user's guild
   *
   * @throws MissingPermissionsException
   */
  public function setCustomRankNames(array $names): void {
    if(!$this->user->isAllowed("guild", "changeRankNames")) {
      throw new MissingPermissionsException();
    }
    $gid = $this->user->identity->guild;
    foreach($names as $rank => $name) {
      if($name === "") {
        continue;
      }
      $rank = (int) substr($rank, 4, 1);
      $row = $this->orm->guildRanksCustom->getByGuildAndRank($gid, $rank);
      if($row === null) {
        $row = new GuildRankCustom();
        $this->orm->guildRanksCustom->attach($row);
        $row->guild = $gid;
        $row->rank = $rank;
      }
      $row->name = $name;
      $this->orm->guildRanksCustom->persist($row);
    }
    $this->orm->guildRanksCustom->flush();
  }

  /**
   * @throws NotInGuildException
   * @throws InsufficientFundsException
   */
  public function donate(int $amount): void {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    if($character->guild === null) {
      throw new NotInGuildException();
    }
    if($character->money < $amount) {
      throw new InsufficientFundsException();
    }
    $character->money -= $amount;
    $character->guild->money += $amount;
    $donation = new GuildDonation();
    $donation->guild = $character->guild;
    $donation->amount = $amount;
    $character->guildDonations->add($donation);
    $this->orm->characters->persistAndFlush($character);
  }
}
?>