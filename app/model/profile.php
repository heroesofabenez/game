<?php
namespace HeroesofAbenez;

  /**
   * Model Profile
   * 
   * @author Jakub Konečný
   */
class Profile extends \Nette\Object {
  /**
   * Gets basic data about specified player
   * @param integer $id character's id
   * @param Nette\Database\Context $db Database context
   * @return array info about character
   */
  static function view($id, \Nette\Database\Context $db) {
    $return = array();
    $char = $db->table("characters")->get($id);
    if(!$char) { return false; }
    $stats = array(
      "name", "gender", "level", "race", "description", "strength",
      "dexterity", "constitution", "intelligence", "charisma"
    );
    foreach($stats as $stat) {
      $return[$stat] = $char->$stat;
    }
    
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
      $guildRank = $db->table("guild_ranks")->get($char->guildrank);
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