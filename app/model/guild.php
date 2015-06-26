<?php
namespace HeroesofAbenez;

/**
 * Data structure for guild
 * 
 * @author Jakub Konečný
 */
class Guild extends \Nette\Object {
  /** @var int id */
  public $id;
  /** @var string name */
  public $name;
  /** @var string description */
  public $description;
  /** @var int number of members */
  public $members;
  /** @var string name of leader */
  public $leader;
  
  /**
   * @param int $id id
   * @param string $name name
   * @param string $description description
   * @param int $members number of members
   * @param string $leader name of leader
   */
  function __construct($id, $name, $description, $members = 0, $leader = "") {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->members = $members;
    $this->leader = $leader;
  }
}

  /**
   * Model Guild
   * 
   * @author Jakub Konečný
   */
class GuildModel extends \Nette\Object {
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var HeroesofAbenez\Profile */
  protected $profileModel;
  /** @var \HeroesofAbenez\Permissions */
  protected $permissionsModel;
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, \Nette\Security\User $user, \HeroesofAbenez\Profile $profileModel, \HeroesofAbenez\Permissions $permissionsModel) {
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
  function getGuildName($id) {
    $guilds = $this->listOfGuilds();
    return $guilds[$id]->name;
  }
  
  /**
   * Get data about specified guild
   * 
   * @param int $id Id of guild
   * @return \HeroesofAbenez\Guild
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
      $rank = $this->permissionsModel->getRoleName($member->guildrank);
      $return["members"][] = array("id" => $member->id, "name" => $member->name, "rank" => ucfirst($rank));
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
      $rank = $this->permissionsModel->getRoleName($member->guildrank);
      $return[] = (object) array("id" => $member->id, "name" => $member->name, "rank" => ucfirst($rank), "rankId" => $member->guildrank);
    }
    return $return;
  }
  
  /**
   * Creates a guild
   * 
   * @param array $data Name and description
   * @return bool Whetever the action was successful
   */
  function create($data) {
    $guilds = $this->cache->load("guilds");
    foreach($guilds as $guild) {
      if($guild->name == $data["name"]) return false;
    }
    $row = $this->db->table("guilds")->insert($data);
    $data2 = array("guild" => $row->id, "guildrank" => 7);
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data2, $this->user->id);
    $this->cache->remove("guilds");
    return true;
  }
  
  /**
   * Send application to a guild
   * 
   * @param int $gid Guild to join
   * @return bool|-1
   */
  function sendApplication($gid) {
    $guild = $this->db->table("guilds")->get($gid);
    if(!$guild) { return -1; }
    $leader = $this->db->table("characters")
      ->where("guild", $gid)
      ->where("guildrank", 7);
    $leader = $leader[1];
    $data = array(
      "from" => $this->user->id, "to" => $leader->id, "type" => "guild_app"
    );
    $row = $this->db->query("INSERT INTO requests", $data);
    if($row) return true;
    else return false;
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
      $return[] = new Request($app->id, $from->name, $to, $app->type, $app->sent, $app->status);
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
        $return[$guild->id] = new Guild($guild->id, $guild->name, $guild->description, $members->count(), $leader);
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
   * @return int Error code/1 on success
   */
  function promote($id) {
    $admin = $this->user;
    if($admin->identity->guild == 0) return 2;
    if(!$admin->isAllowed("guild", "promote")) return 3;
    $character = $this->db->table("characters")->get($id);
    if(!$character) return 4;
    if($character->guild !== $admin->identity->guild) return 5;
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) return 6;
    if($character->guildrank >= 6) return 7;
    if($character->guildrank == 5) {
      $deputy = $this->guildMembers($admin->identity->guild, array(6));
      if(count($deputy) > 0) return 8;
    }
    $this->db->query("UPDATE characters SET guildrank=guildrank+1 WHERE id=$id");
    return 1;
  }
  
  /**
   * Decrease rank of specified member of guild
   * 
   * @param int $id Id of player to be demoted
   * @return int Error code/1 on success
   */
  function demote($id) {
    $admin = $this->user;
    if($admin->identity->guild == 0) return 2;
    if(!$admin->isAllowed("guild", "promote")) return 3;
    $character = $this->db->table("characters")->get($id);
    if(!$character) return 4;
    if($character->guild !== $admin->identity->guild) return 5;
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) return 6;
    if($character->guildrank === 1) return 7;
    $this->db->query("UPDATE characters SET guildrank=guildrank-1 WHERE id=$id");
    return 1;
  }
  
  /**
   * Kick specified member from guild
   * 
   * @param int $id Id of player to be kicked
   * @return int Error code/1 on success
   */
  function kick($id) {
    $admin = $this->user;
    if($admin->identity->guild == 0) return 2;
    if(!$admin->isAllowed("guild", "kick")) return 3;
    $character = $this->db->table("characters")->get($id);
    if(!$character) return 4;
    if($character->guild !== $admin->identity->guild) return 5;
    $roles = $this->permissionsModel->getRoles();
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) return 6;
    $this->db->query("UPDATE characters SET guildrank=NULL, guild=0 WHERE id=$id");
    $this->cache->remove("guilds");
    return 1;
  }
  
  /**
   * Leave the guild
   * 
   * @return bool
  */
  function leave() {
    if($this->user->isInRole("grandmaster")) return false;
    $data = array(
      "guild" => 0, "guildrank" => NULL
    );
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $this->user->id);
    $this->cache->remove("guilds");
    return true;
  }
  
  /**
   * Dissolve guild
   *
   * @param type $id Guild to dissolve
   * @return bool
   */
  function dissolve($id) {
    $members = $this->db->table("characters")->where("guild", $id);
    $data1 = array("guild" => 0, "guildrank" => NULL);
    foreach($members as $member) {
      $this->db->query("UPDATE characters SET ? WHERE id=?", $data1, $member->id);
    }
    $this->db->query("DELETE FROM guilds WHERE id=?", $id);
    $this->cache->remove("guilds");
    return true;
  }
  
  /**
   * Rename guild
   *
   * @param int $id Guild to rename
   * @param string $name New name
   * @return bool Whetever the action was successful
  */
  function rename($id, $name) {
    $guilds = $this->cache->load("guilds");
    foreach($guilds as $guild) {
      if($guild->name == $name) return false;
    }
    $data = array("name" => $name);
    $this->db->query("UPDATE guilds SET ? WHERE id=?", $data, $id);
    $this->cache->remove("guilds");
    return true;
  }
  
  /**
   * 
   * @param int $id Guild's id
   * @param string $description New description
   * @return int Error code/1 on success
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
    if(!$found) return false;
    $data = array("description" => $description);
    $result = $this->db->query("UPDATE guilds SET ? WHERE id=?", $data, $id);
    if($result) {
      $this->cache->remove("guilds");
      return 1;
    } else {
      return 3;
    }
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