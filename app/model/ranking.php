<?php
  /**
   * Model Ranking
   * 
   * @author Jakub Konečný
   */
class Ranking extends Nette\Object {
  /**
   * Gets list of all characters
   * @param Nette\Database\Context $db Database context
   * @return array List of all characters (id, name, level, guild)
   */
  static function characters(Nette\Database\Context $db) {
    $characters = $db->table("characters")->order("level, experience, id");
    $chars = array();
    foreach($characters as $character) {
      if($character->guild == 0) {
        $guildName = "";
      } else {
        $guild = $db->table("guilds")->get($character->guild);
        $guildName = $guild->name;
      }
      $chars[] = array(
        "name" => $character->name, "level" => $character->level,
        "guild" => $guildName, "id" => $character->id
      );
    }
    return $chars;
  }
  
  /**
   * Gets list of all guilds
   * @param Nette\Database\Context $db Database context
   * @return array List of all guilds (name, number of members)
   */
  static function guilds(Nette\Database\Context $db) {
    $return = array();
    $guilds = $db->table("guilds");
    foreach($guilds as $guild) {
      if($guild->id == 0) continue;
      $members = $db->table("characters");
      $count = 0;
      foreach($members as $member) {
        if($member->guild == $guild->id) $count++;
      }
      $return[] = new Guild($guild->id, $guild->name, "", $count);
    }
    return $return;
  }
}
?>
