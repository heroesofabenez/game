<?php
class Profile extends Nette\Object {
  static function view($id, $db) {
    $return = array();
    $char = $db->table("characters")->get($id);
    $return["name"] = $char->name;
    $return["gender"] = $char->gender;
    $return["level"] = $char->level;
    $return["race"] = $char->race;
    $return["description"] = $char->description;
    $return["strength"] = $char->strength;
    $return["dexterity"] = $char->dexterity;
    $return["constitution"] = $char->constitution;
    $return["intelligence"] = $char->intelligence;
    $return["charisma"] = $char->charisma;
    $return["description"] = $char->description;
    
    $race = $db->table("character_races")->get($char->race);
    $return["race"] = $race->name;
    $occupation = $db->table("character_classess")->get($char->occupation);
    $return["occupation"] = $occupation->name;
    if($char->specialization > 0) {
      $return["specialization"] = "-" . $char->specialization;
    } else {
      $return["specialization"] = "";
    }
    if($char->guild > 0) {
      $guild = $db->table("guilds")->get($char->guild);
      $guildRank = $db->table("guild_ranks")->get($char->guild_rank);
      $return["guild"] = "Guild: $guild->name<br>Position in guild: " . ucfirst($guildRank->name);
    } else {
      $return["guild"] = "Not a member of guild";
    }
    $activePet = $db->table("pets")->where("owner=$char->id")->where("deployed=1");
    if($activePet->count("*") == 1) {
      $petType = $db->table("pet_types")->get($activePet->type);
      if($activePet->name == "pets") $petName = "Unnamed"; else $petName = $activePet->name . ",";
      $bonusStat = strtoupper($petType->bonus_stat);
      $return["active_pet"] = "Active pet: $petName $petType->name, +$petType->bonus_value% $bonusStat";
    } else {
      $return["active_pet"] = "No active pet";
    }
    return $return;
  }
}
?>