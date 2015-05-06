<?php
class Guild extends Nette\Object {
  static function view($id, $db) {
    $return = array();
    $guild = $db->table("guilds")->get($id);
    $return["name"] = $guild->name;
    $return["description"] = $guild->description;
    $members = $db->table("characters")->where("guild", $guild->id)->order("guild_rank DESC, id");
    $return["members"] = array();
    foreach($members as $member) {
      $return["members"][] = array("name" => $member->name, "rank" => ucfirst($member->rank->name));
    }
    return $return;
  }
  static function join($db) {
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