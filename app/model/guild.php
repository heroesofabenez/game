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
  /**
   * Get name of specified guild
   * 
   * @param int $id Id of guild
   * @param \Nette\Di\Container $container
   * @return string
   */
  static function getGuildName($id, \Nette\Di\Container $container) {
    $guilds = GuildModel::listOfGuilds($container);
    return $guilds[$id]->name;
  }
  
  /**
   * Get data about specified guild
   * 
   * @param int $id Id of guild
   * @param \Nette\Di\Container $container
   */
  static function guildData($id, \Nette\Di\Container $container) {
    $guilds = GuildModel::listOfGuilds($container);
    return $guilds[$id];
  }
  
  /**
   * Gets basic data about specified guild
   * @param integer $id guild's id
   * @param \Nette\Di\Container $container
   * @return array info about guild
   */
  static function view($id, \Nette\Di\Container $container) {
    $return = array();
    $db = $container->getService("database.default.context");
    $guilds = GuildModel::listOfGuilds($container);
    $guild = \Nette\Utils\Arrays::get($guilds, $id, false);
    if(!$guild) { return false; }
    $return["name"] = $guild->name;
    $return["description"] = $guild->description;
    $members = $db->table("characters")->where("guild", $guild->id)->order("guildrank DESC, id");
    $return["members"] = array();
    foreach($members as $member) {
      $rank = Profile::getRankName($member->guildrank, $container);
      $return["members"][] = array("name" => $member->name, "rank" => ucfirst($rank));
    }
    return $return;
  }
  
  /**
   * Get members of specified guild
   * 
   * @param type $id Id of guild
   * @param \Nette\Di\Container $container
   * @return array
   */
  static function guildMembers($id, \Nette\Di\Container $container) {
    $return = array();
    $db = $container->getService("database.default.context");
    $members = $db->table("characters")->where("guild", $id)->order("guildrank DESC, id");
    foreach($members as $member) {
      $rank = Profile::getRankName($member->guildrank, $container);
      $return[] = array("id" => $member->id, "name" => $member->name, "rank" => ucfirst($rank), "rankId" => $member->guildrank);
    }
    return $return;
  }
  
  /**
   * Creates a guild
   * 
   * @param array $data Name and description
   * @param int $founder Id of founder
   * @param \Nette\Di\Container $container
   * @return bool Whetever the action was successful
   */
  static function create($data, $founder, \Nette\Di\Container $container) {
    $cache = $container->getService("caches.guilds");
    $guilds = $cache->load("guilds");
    foreach($guilds as $guild) {
      if($guild->name == $data["name"]) return false;
    }
    $db = $container->getService("database.default.context");
    $row = $db->table("guilds")->insert($data);
    $data2 = array("guild" => $row->id, "guildrank" => 7);
    $db->query("UPDATE characters SET ? WHERE id=?", $data2, $founder);
    $cache->remove("guilds");
    return true;
  }
  
  /**
   * Send application to a guild
   * 
   * @param int $gid Guild to join
   * @param int $uid Player's id
   * @param \Nette\Database\Context $db Database context
   * @return bool|-1
   */
  static function sendApplication($gid, $uid, \Nette\Database\Context $db) {
    $guild = $db->table("guilds")->get($gid);
    if(!$guild) { return -1; }
    $leader = $db->table("characters")
      ->where("guild", $gid)
      ->where("guildrank", 7);
    $leader = $leader[1];
    $data = array(
      "from" => $uid, "to" => $leader->id, "type" => "guild_app"
    );
    $row = $db->query("INSERT INTO requests", $data);
    if($row) return true;
  }
  
  /**
   * Check if player has an unresolved application
   * 
   * @param int $id Player's id
   * @param \Nette\Database\Context $db Database context
   */
  static function haveUnresolvedApplication($id, \Nette\Database\Context $db) {
    $apps = $db->table("requests")
      ->where("from", $id)
      ->where("type", "guild_app")
      ->where("status", "new");
    if($apps->count("*") > 0) return true;
    else return false;
  }
  
  /**
   * Get unresolved applications to specified guild
   * 
   * @param int $id Guild's id
   * @param \Nette\Di\Container $container
   * @return array
   */
  static function showApplications($id, \Nette\Di\Container $container) {
    $return = array();
    $guilds = GuildModel::listOfGuilds($container);
    $guild = $guilds[$id];
    $db = $container->getService("database.default.context");
    $leaderRow = $db->table("characters")
      ->where("name", $guild->leader);
    foreach($leaderRow as $leader) { }
    $apps = $db->table("requests")
      ->where("to", $leader->id)
      ->where("type", "guild_app")
      ->where("status", "new");
    foreach($apps as $app) {
      $from = $db->table("characters")->get($app->from);
      $to = $leader->name;
      $return[] = new Request($app->id, $from->name, $to, $app->type, $app->sent, $app->status);
    }
    return $return;
  }
  
  /**
   * Gets list of guilds
   * 
   * @param \Nette\Di\Container $container
   * @return array list of guilds (id, name, description, leader)
   */
  static function listOfGuilds(\Nette\Di\Container $container) {
    $cache = $container->getService("caches.guilds");
    $guilds = $cache->load("guilds");
    $return = array();
    if($guilds === NULL) {
      $db = $container->getService("database.default.context");
      $guilds = $db->table("guilds");
      foreach($guilds as $guild) {
        if($guild->id == 0) continue;
        $members = $db->table("characters")->where("guild", $guild->id);
        $count = 0;
        $leader = "";
        foreach($members as $member) {
          if($member->guild == $guild->id) $count++;
          if($member->guildrank == 7) { $leader = $member->name; }
        }
        $return[$guild->id] = new Guild($guild->id, $guild->name, $guild->description, $count, $leader);
      }
      $cache->save("guilds", $return);
    } else {
      $return = $guilds;
    }
    return $return;
  }
  
  /**
   * Increase rank of specified member of guild
   * 
   * @param int $id Id of player to be demoted
   * @param \Nette\Di\Container $container
   * @return int Error code/1 on success
   */
  static function promote($id, \Nette\Di\Container $container) {
    $admin = $container->getService("security.user");
    if($admin->identity->guild == 0) return 2;
    if(!$admin->isAllowed("guild", "promote")) return 3;
    $db = $container->getService("database.default.context");
    $character = $db->table("characters")->get($id);
    if(!$character) return 4;
    if($character->guild !== $admin->identity->guild) return 5;
    $roles = Authorizator::getRoles($container);
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) return 6;
    if($character->guildrank >= 6) return 7;
    $db->query("UPDATE characters SET guildrank=guildrank+1 WHERE id=$id");
    return 1;
  }
  
  /**
   * Decrease rank of specified member of guild
   * 
   * @param int $id Id of player to be demoted
   * @param \Nette\Di\Container $container
   * @return int Error code/1 on success
   */
  static function demote($id, \Nette\Di\Container $container) {
    $admin = $container->getService("security.user");
    if($admin->identity->guild == 0) return 2;
    if(!$admin->isAllowed("guild", "demote")) return 3;
    $db = $container->getService("database.default.context");
    $character = $db->table("characters")->get($id);
    if(!$character) return 4;
    if($character->guild !== $admin->identity->guild) return 5;
    $roles = Authorizator::getRoles($container);
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) return 6;
    if($character->guildrank === 1) return 7;
    $db->query("UPDATE characters SET guildrank=guildrank-1 WHERE id=$id");
    return 1;
  }
  
  /**
   * Kick specified member from guild
   * 
   * @param int $id Id of player to be kicked
   * @param \Nette\Di\Container $container
   * @return int Error code/1 on success
   */
  static function kick($id, \Nette\Di\Container $container) {
    $admin = $container->getService("security.user");
    if($admin->identity->guild == 0) return 2;
    if(!$admin->isAllowed("guild", "kick")) return 3;
    $db = $container->getService("database.default.context");
    $character = $db->table("characters")->get($id);
    if(!$character) return 4;
    if($character->guild !== $admin->identity->guild) return 5;
    $roles = Authorizator::getRoles($container);
    foreach($roles as $role) {
      if($role["name"] == $admin->roles[0]) {
        $adminRole = $role["id"];
        break;
      }
    }
    if($adminRole <= $character->guildrank) return 6;
    $db->query("UPDATE characters SET guildrank=NULL, guild=0 WHERE id=$id");
    $cache = $container->getService("caches.guilds");
    $cache->remove("guilds");
    return 1;
  }
  
  /**
   * Leave the guild
   * 
   * @param \Nette\Di\Container $container
   * @param int $id Player's id
   * @return void
  */
  static function leave(\Nette\Di\Container $container, $id) {
    $db = $container->getService("database.default.context");
    $data = array(
      "guild" => 0, "guildrank" => NULL
    );
    $db->query("UPDATE characters SET ? WHERE id=?", $data, $id);
    $cache = $container->getService("caches.guilds");
    $cache->remove("guilds");
  }
  
  /**
   * Dissolve guild
   *
   * @param type $id Guild to dissolve
   * @param \Nette\Di\Container $container
   */
  static function dissolve($id, \Nette\Di\Container $container) {
    $cache = $container->getService("caches.guilds");
    $db = $container->getService("database.default.context");
    $members = $db->table("characters")->where("guild", $id);
    $data1 = array("guild" => 0, "guildrank" => NULL);
    foreach($members as $member) {
      $db->query("UPDATE characters SET ? WHERE id=?", $data1, $member->id);
    }
    $db->query("DELETE FROM guilds WHERE id=?", $id);
    $cache->remove("guilds");
    return true;
  }
  
  /**
   * Rename guild
   *
   * @param int $id Guild to rename
   * @param string $name New name
   * @param \Nette\Di\Container $container
   * @return bool Whetever the action was successful
  */
  static function rename($id, $name, \Nette\Di\Container $container) {
    $cache = $container->getService("caches.guilds");
    $guilds = $cache->load("guilds");
    foreach($guilds as $guild) {
      if($guild->name == $name) return false;
    }
    $db = $container->getService("database.default.context");
    $data = array("name" => $name);
    $db->query("UPDATE guilds SET ? WHERE id=?", $data, $id);
    $cache->remove("guilds");
    return true;
  }
  
  /**
   * 
   * @param int $id Guild's id
   * @param string $description New description
   * @param \Nette\Di\Container $container
   * @return int Error code/1 on success
   */
  static function changeDescription($id, $description,  \Nette\Di\Container $container) {
    $cache = $container->getService("caches.guilds");
    $guilds = $cache->load("guilds");
    $found = false;
    foreach($guilds as $guild) {
      if($guild->id == $id) {
        $found = true;
        break;
      }
    }
    if(!$found) return false;
    $db = $container->getService("database.default.context");
    $data = array("description" => $description);
    $result = $db->query("UPDATE guilds SET ? WHERE id=?", $data, $id);
    if($result) {
      $cache->remove("guilds");
      return 1;
    } else {
      return 3;
    }
  }
}
?>