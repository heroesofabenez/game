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
   * Get name of specified rank
   * 
   * @param int $id
   * @param \Nette\Di\Container $container
   * @return string
   */
  static function getRankName($id, \Nette\Di\Container $container) {
    $ranks = Authorizator::getRoles($container);
    return $ranks[$id]["name"];
  }
  
  /**
   * @param \Nette\Di\Container $container
   * @return array
   */
  static function getCharacters(\Nette\Di\Container $container) {
    $return = array();
    $cache = $container->getService("caches.characters");
    $characters = $cache->load("characters");
    if($characters === NULL) {
      $db = $container->getService("database.default.context");
      $characters = $db->table("characters");
      foreach($characters as $char) {
        $return[$char->id] = array(
          "id" => $char->id, "name" => $char->name
        );
      }
      $cache->save("characters", $return);
    } else {
      $return = $characters;
    }
    return $return;
  }
  
  /**
   * Get character's id
   * 
   * @param string $name Character's name
   * @param \Nette\Di\Container $container
   * @return int
   */
  static function getCharacterId($name, \Nette\Di\Container $container) {
    $characters = Profile::getCharacters($container);
    foreach($characters as $char) {
      if($char["name"] == $name) return $char["id"];
    }
    return 0;
  }
  
  /**
   * Get character's name
   * 
   * @param int $id Character's id
   * @param \Nette\Di\Container $container
   * @return string
   */
  static function getCharacterName($id, \Nette\Di\Container $container) {
    $characters = Profile::getCharacters($container);
    return $characters[$id]["name"];
  }
  
  /**
   * Get character's guild
   * 
   * @param string $id Character's id
   * @param \Nette\Database\Context $db Database context
   * @return int
   */
  static function getCharacterGuild($id, \Nette\Database\Context $db) {
    $char = $db->table("characters")->get($id);
    if(!$char) return 0;
    return $char->guild;
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
      $guildName = GuildModel::getGuildName($char->guild, $container);
      $guildRank = Profile::getRankName($char->guildrank, $container);
      $return["guild"] = "Guild: $guildName<br>Position in guild: " . ucfirst($guildRank);
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