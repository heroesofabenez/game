<?php
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
class GuildModel extends Nette\Object {
  /**
   * Gets basic data about specified guild
   * @param integer $id guild's id
   * @param Nette\Database\Context $db Database context
   * @return array info about guild
   */
  static function view($id, Nette\Database\Context  $db) {
    $return = array();
    $guild = $db->table("guilds")->get($id);
    if(!$guild) { return false; }
    $return["name"] = $guild->name;
    $return["description"] = $guild->description;
    $members = $db->table("characters")->where("guild", $guild->id)->order("guild_rank DESC, id");
    $return["members"] = array();
    foreach($members as $member) {
      $return["members"][] = array("name" => $member->name, "rank" => ucfirst($member->rank->name));
    }
    return $return;
  }
  
  /**
   * Gets list of guilds
   * @param Nette\Database\Context $db Database context
   * @return array list of guilds (id, name, description, leader)
   */
  static function listOfGuilds(Nette\Database\Context $db) {
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
      $return[] = new Guild($guild->id, $guild->name, $guild->description, $leader, $members->count("*"));
    }
    return $return;
  }
}
?>