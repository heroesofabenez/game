<?php
namespace HeroesofAbenez;

/**
 * Data structure for guild
 * 
 * @author Jakub Konečný
 */
class Guild {
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
   * Gets basic data about specified guild
   * @param integer $id guild's id
   * @param Nette\Database\Context $db Database context
   * @return array info about guild
   */
  static function view($id, \Nette\Database\Context  $db) {
    $return = array();
    $guild = $db->table("guilds")->get($id);
    if(!$guild) { return false; }
    $return["name"] = $guild->name;
    $return["description"] = $guild->description;
    $members = $db->table("characters")->where("guild", $guild->id)->order("guildrank DESC, id");
    $return["members"] = array();
    foreach($members as $member) {
      $return["members"][] = array("name" => $member->name, "rank" => ucfirst($member->rank->name));
    }
    return $return;
  }
  
  /**
   * Get id of player's guild
   * 
   * @param Nette\Database\Context $db Database context
   * @param int $uid Player's id
   * @return int
   */
  static function getGuildId(\Nette\Database\Context $db, $uid) {
    $char = $db->table("characters")->get($uid);
    return $char->guild;
  }
  
  /**
   * Creates a guild
   * 
   * @param array $data Name and description
   * @param int $founder Id of founder
   * @param Nette\Database\Context $db Database context
   * @return bool Whetever the action was successful
   */
  static function create($data, $founder, \Nette\Database\Context  $db) {
    $row = $db->table("guilds")->insert($data);
    $data2 = array("guild" => $row->id, "guildrank" => 8);
    $db->query("UPDATE characters SET ? WHERE id=?", $data2, $founder);
    return true;
  }
  
  /**
   * Send application to a guild
   * 
   * @param int $gid Guild to join
   * @param int $uid Player's id
   * @param Nette\Database\Context $db Database context
   * @return bool|-1
   */
  static function sendApplication($gid, $uid, \Nette\Database\Context $db) {
    $guild = $db->table("guilds")->get($gid);
    if(!$guild) { return -1; }
    $leader = $db->table("characters")
      ->where("guild", $gid)
      ->where("guildrank", 8);
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
   * @param Nette\Database\Context $db Database context
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
   * Gets list of guilds
   * 
   * @param Nette\Database\Context $db Database context
   * @return array list of guilds (id, name, description, leader)
   */
  static function listOfGuilds(\Nette\Database\Context $db) {
    $return = array();
    $guilds = $db->table("guilds");
    foreach($guilds as $guild) {
      if($guild->id == 0) continue;
      $members = $db->table("characters")->where("guild", $guild->id);
      foreach($members as $member) {
        if($member->rank->name == "grandmaster") {
          $leader = $member->name;
          break;
        }
      }
      $return[] = new Guild($guild->id, $guild->name, $guild->description, $members->count("*"), $leader);
    }
    return $return;
  }
}
?>