<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Request as RequestEntity,
    HeroesofAbenez\Entities\Guild as GuildEntity,
    Nette\Utils\Arrays;

  /**
   * Model Guild
   * 
   * @author Jakub Konečný
   */
class Guild {
  use \Nette\SmartObject;
  
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\Model\Profile */
  protected $profileModel;
  /** @var \HeroesofAbenez\Model\Permissions */
  protected $permissionsModel;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   * @param \Nette\Security\User $user
   * @param Profile $profileModel
   * @param Permissions $permissionsModel
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, \Nette\Security\User $user, Profile $profileModel, Permissions $permissionsModel) {
    $this->cache = $cache;
    $this->db = $db;
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
    $guild = Arrays::get($this->listOfGuilds(), $id, false);
    if(!$guild) {
      return "";
    } else {
      return $guild->name;
    }
  }
  
  /**
   * Get data about specified guild
   * 
   * @param int $id Id of guild
   * @return GuildEntity
   */
  function guildData(int $id) {
    $guilds = $this->listOfGuilds();
    return $guilds[$id];
  }
  
  /**
   * Gets basic data about specified guild
   * 
   * @param integer $id guild's id
   * @return array|bool info about guild
   */
  function view(int $id) {
    $return = [];
    $guilds = $this->listOfGuilds();
    $guild = Arrays::get($guilds, $id, false);
    if(!$guild) {
      return false;
    }
    $return["name"] = $guild->name;
    $return["description"] = $guild->description;
    $members = $this->db->table("characters")->where("guild", $guild->id)->order("guildrank DESC, id");
    $return["members"] = [];
    foreach($members as $member) {
      $return["members"][] = (object) ["id" => $member->id, "name" => $member->name, "rank" => $member->guildrank];
    }
    return $return;
  }
  
  /**
   * Get a guild's custom name for a rank
   * 
   * @param int $guild
   * @param int $rank
   * @return string
   */
  function getCustomRankName(int $guild, int $rank): string {
    $customRank = $this->db->table("guild_ranks_custom")->where("guild=? AND rank=?", $guild, $rank);
    if(count($customRank)) {
      return $customRank->fetch()->name;
    } else {
      return "";
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
    $members = $this->db->table("characters")->where("guild", $id)->order("guildrank DESC, id");
    if(count($roles) > 0) $members->where("guildrank", $roles);
    foreach($members as $member) {
      $rank = $member->guildrank;
      $m = (object) ["id" => $member->id, "name" => $member->name, "rank" => $rank, "rankId" => $member->guildrank, "customRankName" => ""];
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
    $guilds = $this->cache->load("guilds");
    foreach($guilds as $guild) {
      if($guild->name == $data["name"]) throw new NameInUseException();
    }
    $row = $this->db->table("guilds")->insert($data);
    $data2 = ["guild" => $row->id, "guildrank" => 7];
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data2, $this->user->id);
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
    $guild = $this->db->table("guilds")->get($gid);
    if(!$guild) { throw new GuildNotFoundException; }
    $leader = $this->db->table("characters")
      ->where("guild", $gid)
      ->where("guildrank", 7);
    $leader = $leader[1];
    $data = [
      "from" => $this->user->id, "to" => $leader->id, "type" => "guild_app"
    ];
    $this->db->query("INSERT INTO requests", $data);
  }
  
  /**
   * Check if player has an unresolved application
   * 
   * @return bool
   */
  function haveUnresolvedApplication(): bool {
    $apps = $this->db->table("requests")
      ->where("from", $this->user->id)
      ->where("type", "guild_app")
      ->where("status", "new");
    if($apps->count() > 0) {
      return true;
    } else {
      return false;
    }
  }
  
  /**
   * Get unresolved applications to specified guild
   * 
   * @param int $id Guild's id
   * @return RequestEntity[]
   */
  function showApplications(int $id): array {
    $return = [];
    $guilds = $this->listOfGuilds();
    $guild = $guilds[$id];
    $leaderId = $this->profileModel->getCharacterId($guild->leader);
    $apps = $this->db->table("requests")
      ->where("to", $leaderId)
      ->where("type", "guild_app")
      ->where("status", "new");
    foreach($apps as $app) {
      $from = $this->db->table("characters")->get($app->from);
      $to = $guild->leader;
      $return[] = new RequestEntity($app->id, $from->name, $to, $app->type, $app->sent, $app->status);
    }
    return $return;
  }
  
  /**
   * Gets list of guilds
   *
   * @return GuildEntity[] list of guilds (id, name, description, leader)
   */
  function listOfGuilds(): array {
    $return = [];
    $guilds = $this->cache->load("guilds");
    if($guilds === NULL) {
      $guilds = $this->db->table("guilds");
      foreach($guilds as $guild) {
        if($guild->id == 0) continue;
        $members = $this->db->table("characters")->where("guild", $guild->id);
        $leader = "";
        foreach($members as $member) {
          if($member->guildrank == 7) {
            $leader = $member->name;
          }
        }
        $return[$guild->id] = new GuildEntity($guild->id, $guild->name, $guild->description, $members->count(), $leader);
      }
      $this->cache->save("guilds", $return);
    } else {
      $return = $guilds;
    }
    return $return;
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
    $character = $this->db->table("characters")->get($id);
    if(!$character) {
      throw new PlayerNotFoundException;
    }
    if($character->guild !== $admin->identity->guild) {
      throw new PlayerNotInGuildException;
    }
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) {
      throw new CannotPromoteHigherRanksException;
    }
    if($character->guildrank >= 6) {
      throw new CannotPromoteToGrandmasterException;
    }
    if($character->guildrank == 5) {
      $deputy = $this->guildMembers($admin->identity->guild, [6]);
      if(count($deputy) > 0) throw new CannotHaveMoreDeputiesException;
    }
    $this->db->query("UPDATE characters SET guildrank=guildrank+1 WHERE id=$id");
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
    $character = $this->db->table("characters")->get($id);
    if(!$character) {
      throw new PlayerNotFoundException;
    }
    if($character->guild !== $admin->identity->guild) {
      throw new PlayerNotInGuildException;
    }
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) {
      throw new CannotDemoteHigherRanksException;
    }
    if($character->guildrank === 1) {
      throw new CannotDemoteLowestRankException;
    }
    $this->db->query("UPDATE characters SET guildrank=guildrank-1 WHERE id=$id");
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
    $character = $this->db->table("characters")->get($id);
    if(!$character) {
      throw new PlayerNotFoundException;
    }
    if($character->guild !== $admin->identity->guild) {
      throw new PlayerNotInGuildException;
    }
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) {
      throw new CannotKickHigherRanksException;
    }
    $this->db->query("UPDATE characters SET guildrank=NULL, guild=0 WHERE id=$id");
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
    $data = [
      "guild" => 0, "guildrank" => NULL
    ];
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $this->user->id);
    $this->cache->remove("guilds");
  }
  
  /**
   * Dissolve guild
   *
   * @param int $id Guild to dissolve
   * @return void
   */
  function dissolve(int $id): void {
    $members = $this->db->table("characters")->where("guild", $id);
    $data1 = ["guild" => 0, "guildrank" => NULL];
    foreach($members as $member) {
      $this->db->query("UPDATE characters SET ? WHERE id=?", $data1, $member->id);
    }
    $this->db->query("DELETE FROM guilds WHERE id=?", $id);
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
    $guilds = $this->cache->load("guilds");
    foreach($guilds as $guild) {
      if($guild->name == $name) {
        throw new NameInUseException;
      }
    }
    $data = ["name" => $name];
    $this->db->query("UPDATE guilds SET ? WHERE id=?", $data, $id);
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
    $guilds = $this->cache->load("guilds");
    $found = false;
    foreach($guilds as $guild) {
      if($guild->id == $id) {
        $found = true;
        break;
      }
    }
    if(!$found) {
      throw new GuildNotFoundException;
    }
    $data = ["description" => $description];
    $this->db->query("UPDATE guilds SET ? WHERE id=?", $data, $id);
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
    $data = ["guild" => $gid, "guildrank" => 1];
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $uid);
    $this->cache->remove("guilds");
  }
  
  /**
   * Get default rank names
   *
   * @return string[]
   */
  function getDefaultRankNames() {
    return $this->db->table("guild_ranks")->order("id")->fetchPairs("id", "name");
  }
  
  /**
   * Get custom rank names for a guild
   *
   * @param int $id
   * @return string[]
   */
  function getCustomRankNames(int $id): array {
    return $this->db->table("guild_ranks_custom")->where("guild=?", $id)->fetchPairs("rank", "name");
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
    $tableName = "guild_ranks_custom";
    foreach($names as $rank => $name) {
      if($name === "") continue;
      $rank = substr($rank, 4, 1);
      $data = ["guild" => $gid, "rank" => $rank, "name" => $name];
      $row = $this->db->table($tableName)->where("guild=? AND rank=?", $gid, $rank);
      if($row->count("*")) {
        $this->db->query("UPDATE $tableName SET ? WHERE guild=? AND rank=?", $data, $gid, $rank);
      } else {
        $this->db->query("INSERT INTO $tableName", $data);
      }
    }
  }
}
?>