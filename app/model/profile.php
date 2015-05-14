<?php
namespace HeroesofAbenez;

  /**
   * Model Profile
   * 
   * @author Jakub Konečný
   */
class Profile extends \Nette\Object {
  /**
   * Get name of specified race
   * 
   * @param int $id Race's id
   * @param \Nette\Di\Container $container
   * @return string
   */
  static function getRaceName($id, \Nette\Di\Container $container) {
    $racesList = CharacterModel::getRacesList($container);
    return $racesList[$id];
  }
  
  /**
   * Get name of specified class
   * 
   * @param int $id
   * @param \Nette\Di\Container $container
   * @return string
   */
  static function getClassName($id, \Nette\Di\Container $container) {
    $classesList = CharacterModel::getClassesList($container);
    return $classesList[$id];
  }
  
  /**
   * Gets basic data about specified player
   * @param integer $id character's id
   * @param \Nette\Di\Container $container
   * @return array info about character
   */
  static function view($id, \Nette\Di\Container $container) {
    $db = $container->getService("database.default.context");
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
    
    $return["race"] = Profile::getRaceName($char->race, $container);
    $return["occupation"] = Profile::getClassName($char->occupation, $container);
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