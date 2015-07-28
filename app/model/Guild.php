<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Request as RequestEntity,
    HeroesofAbenez\Entities\Guild as GuildEntity,
    Nette\Application\ForbiddenRequestException,
    Nette\Application\ApplicationException,
    Nette\Application\BadRequestException,
    Kdyby\Translation\Translator;

  /**
   * Model Guild
   * 
   * @author Jakub Konečný
   */
class Guild extends \Nette\Object {
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var HeroesofAbenez\Model\Profile */
  protected $profileModel;
  /** @var \HeroesofAbenez\Model\Permissions */
  protected $permissionsModel;
  /** @var \Kdyby\Translation\Translator */
  protected $translator;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   * @param \Nette\Security\User $user
   * @param \HeroesofAbenez\Model\Profile $profileModel
   * @param \HeroesofAbenez\Model\Permissions $permissionsModel
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, \Nette\Security\User $user, Profile $profileModel, Permissions $permissionsModel, Translator $translator) {
    $this->cache = $cache;
    $this->db = $db;
    $this->user = $user;
    $this->profileModel = $profileModel;
    $this->permissionsModel = $permissionsModel;
    $this->translator = $translator;
  }
  
  /**
   * Get name of specified guild
   * 
   * @param int $id Id of guild
   * @return string
   */
  function getGuildName($id) {
    $guilds = $this->listOfGuilds();
    return $guilds[$id]->name;
  }
  
  /**
   * Get data about specified guild
   * 
   * @param int $id Id of guild
   * @return \HeroesofAbenez\Entities\Guild
   */
  function guildData($id) {
    $guilds = $this->listOfGuilds();
    return $guilds[$id];
  }
  
  /**
   * Gets basic data about specified guild
   * @param integer $id guild's id
   * @return array info about guild
   */
  function view($id) {
    $return = array();
    $guilds = $this->listOfGuilds();
    $guild = \Nette\Utils\Arrays::get($guilds, $id, false);
    if(!$guild) { return false; }
    $return["name"] = $guild->name;
    $return["description"] = $guild->description;
    $members = $this->db->table("characters")->where("guild", $guild->id)->order("guildrank DESC, id");
    $return["members"] = array();
    foreach($members as $member) {
      $return["members"][] = (object) array("id" => $member->id, "name" => $member->name, "rank" => $member->guildrank);
    }
    return $return;
  }
  
  /**
   * Get members of specified guild
   * 
   * @param int $id Id of guild
   * @param array $roles Return only members with these roles
   * @return array
   */
  function guildMembers($id, $roles = array()) {
    $return = array();
    $members = $this->db->table("characters")->where("guild", $id)->order("guildrank DESC, id");
    if(count($roles) > 0) $members->where("guildrank", $roles);
    foreach($members as $member) {
      $rank = $member->guildrank;
      $return[] = (object) array("id" => $member->id, "name" => $member->name, "rank" => ucfirst($rank), "rankId" => $member->guildrank);
    }
    return $return;
  }
  
  /**
   * Creates a guild
   * 
   * @param array $data Name and description
   * @return void
   * @throws \Nette\Application\ForbiddenRequestException
   */
  function create($data) {
    $guilds = $this->cache->load("guilds");
    foreach($guilds as $guild) {
      if($guild->name == $data["name"]) throw new ForbiddenRequestException($this->translator->translate("errors.guild.nameTaken"));
    }
    $row = $this->db->table("guilds")->insert($data);
    $data2 = array("guild" => $row->id, "guildrank" => 7);
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data2, $this->user->id);
    $this->cache->remove("guilds");
  }
  
  /**
   * Send application to a guild
   * 
   * @param int $gid Guild to join
   * @return void
   * @throws \Nette\Application\BadRequestException
   */
  function sendApplication($gid) {
    $guild = $this->db->table("guilds")->get($gid);
    if(!$guild) { throw new BadRequestException; }
    $leader = $this->db->table("characters")
      ->where("guild", $gid)
      ->where("guildrank", 7);
    $leader = $leader[1];
    $data = array(
      "from" => $this->user->id, "to" => $leader->id, "type" => "guild_app"
    );
    $this->db->query("INSERT INTO requests", $data);
  }
  
  /**
   * Check if player has an unresolved application
   * 
   * @return bool
   */
  function haveUnresolvedApplication() {
    $apps = $this->db->table("requests")
      ->where("from", $this->user->id)
      ->where("type", "guild_app")
      ->where("status", "new");
    if($apps->count() > 0) return true;
    else return false;
  }
  
  /**
   * Get unresolved applications to specified guild
   * 
   * @param int $id Guild's id
   * @return array
   */
  function showApplications($id) {
    $return = array();
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
   * @return array list of guilds (id, name, description, leader)
   */
  function listOfGuilds() {
    $return = array();
    $guilds = $this->cache->load("guilds");
    if($guilds === NULL) {
      $guilds = $this->db->table("guilds");
      foreach($guilds as $guild) {
        if($guild->id == 0) continue;
        $members = $this->db->table("characters")->where("guild", $guild->id);
        $leader = "";
        foreach($members as $member) {
          if($member->guildrank == 7) $leader = $member->name;
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
   * @throws \Nette\Application\ForbiddenRequestException
   */
  function promote($id) {
    $admin = $this->user;
    if($admin->identity->guild == 0) throw new ForbiddenRequestException($this->translator->translate("errors.guild.notInGuild"));
    if(!$admin->isAllowed("guild", "promote")) throw new ForbiddenRequestException($this->translator->translate("errors.guild.missingPermissions"));
    $character = $this->db->table("characters")->get($id);
    if(!$character) throw new ForbiddenRequestException($this->translator->translate("errors.guild.playerDoesNotExist"));
    if($character->guild !== $admin->identity->guild) throw new ForbiddenRequestException($this->translator->translate("errors.guild.playerNotInGuild"));
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) throw new ForbiddenRequestException($this->translator->translate("errors.guild.cannotPromoteHigherRanks"));
    if($character->guildrank >= 6) throw new ForbiddenRequestException($this->translator->translate("errors.guild.cannotPromoteToGranmaster"));
    if($character->guildrank == 5) {
      $deputy = $this->guildMembers($admin->identity->guild, array(6));
      if(count($deputy) > 0) throw new ForbiddenRequestException($this->translator->translate("errors.guild.cannotHaveMoreDeputies"));
    }
    $this->db->query("UPDATE characters SET guildrank=guildrank+1 WHERE id=$id");
  }
  
  /**
   * Decrease rank of specified member of guild
   * 
   * @param int $id Id of player to be demoted
   * @return void
   * @throws \Nette\Application\ForbiddenRequestException
   */
  function demote($id) {
    $admin = $this->user;
    if($admin->identity->guild == 0) throw new ForbiddenRequestException($this->translator->translate("errors.guild.notInGuild"));
    if(!$admin->isAllowed("guild", "promote")) throw new ForbiddenRequestException($this->translator->translate("errors.guild.missingPermissions"));
    $character = $this->db->table("characters")->get($id);
    if(!$character) throw new ForbiddenRequestException($this->translator->translate("errors.guild.playerDoesNotExist"));
    if($character->guild !== $admin->identity->guild) throw new ForbiddenRequestException($this->translator->translate("errors.guild.playerNotInGuild"));
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) throw new ForbiddenRequestException($this->translator->translate("errors.guild.cannotDemoteHigherRanks"));
    if($character->guildrank === 1) throw new ForbiddenRequestException($this->translator->translate("errors.guild.cannotDemoteLowestRank"));
    $this->db->query("UPDATE characters SET guildrank=guildrank-1 WHERE id=$id");
  }
  
  /**
   * Kick specified member from guild
   * 
   * @param int $id Id of player to be kicked
   * @return void
   * @throws \Nette\Application\ForbiddenRequestException
   */
  function kick($id) {
    $admin = $this->user;
    if($admin->identity->guild == 0) throw new ForbiddenRequestException($this->translator->translate("errors.guild.notInGuild"));
    if(!$admin->isAllowed("guild", "kick")) throw new ForbiddenRequestException($this->translator->translate("errors.guild.missingPermissions"));
    $character = $this->db->table("characters")->get($id);
    if(!$character) throw new ForbiddenRequestException($this->translator->translate("errors.guild.playerDoesNotExist"));
    if($character->guild !== $admin->identity->guild) throw new ForbiddenRequestException($this->translator->translate("errors.guild.playerNotInGuild"));
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) throw new ForbiddenRequestException($this->translator->translate("errors.guild.cannotKickHigherRanks"));
    $this->db->query("UPDATE characters SET guildrank=NULL, guild=0 WHERE id=$id");
    $this->cache->remove("guilds");
  }
  
  /**
   * Leave the guild
   * 
   * @return void
   * @throws \Nette\Application\ForbiddenRequestException
  */
  function leave() {
    if($this->user->identity->guild === 0) throw new ForbiddenRequestException($this->translator->translate("errors.guild.notInGuild"), 201);
    if($this->user->isInRole("grandmaster")) throw new ForbiddenRequestException($this->translator->translate("errors.guild.grandmasterCannotLeave"), 202);
    $data = array(
      "guild" => 0, "guildrank" => NULL
    );
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $this->user->id);
    $this->cache->remove("guilds");
  }
  
  /**
   * Dissolve guild
   *
   * @param type $id Guild to dissolve
   * @return void
   */
  function dissolve($id) {
    $members = $this->db->table("characters")->where("guild", $id);
    $data1 = array("guild" => 0, "guildrank" => NULL);
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
   * @throws \Nette\Application\ApplicationException
  */
  function rename($id, $name) {
    $guilds = $this->cache->load("guilds");
    foreach($guilds as $guild) {
      if($guild->name == $name) throw new ApplicationException($this->translator->translate("errors.guild.nameTaken"));
    }
    $data = array("name" => $name);
    $this->db->query("UPDATE guilds SET ? WHERE id=?", $data, $id);
    $this->cache->remove("guilds");
  }
  
  /**
   * Change description of specified guild
   * 
   * @param int $id Guild's id
   * @param string $description New description
   * @return void
   * @throws \Nette\Application\BadRequestException
   */
  function changeDescription($id, $description) {
    $guilds = $this->cache->load("guilds");
    $found = false;
    foreach($guilds as $guild) {
      if($guild->id == $id) {
        $found = true;
        break;
      }
    }
    if(!$found) throw new BadRequestException;
    $data = array("description" => $description);
    $this->db->query("UPDATE guilds SET ? WHERE id=?", $data, $id);
  }
  
  /**
   * Join a guild
   * 
   * @param int $uid Character's id
   * @param int $gid Guild's id
   * @return void
   */
  function join($uid, $gid) {
    $data = array("guild" => $gid, "guildrank" => 1);
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $uid);
    $this->cache->remove("guilds");
  }
}
?>