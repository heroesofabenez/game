<?php
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
  static function view($id, $db) {
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
  static function listOfGuilds($db) {
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
      $return[] = array("id" => $guild->id, "name" => $guild->name, "description" => $guild->description, "leader" => $leader, "members" => $members->count("*"));
    }
    return $return;
  }
}
?>