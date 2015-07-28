<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Pet;

  /**
   * Model Profile
   * 
   * @author Jakub Konečný
   */
class Profile extends \Nette\Object {
  /** @var \Nette\Database\Context  */
  protected $db;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \HeroesofAbenez\Model\Character */
  protected $characterModel;
  /** @var \HeroesofAbenez\Model\Permissions */
  protected $permissionsModel;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   * @param \HeroesofAbenez\Model\Character $characterModel
   * @param \HeroesofAbenez\Model\Permissions $permissionsModel
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, Character $characterModel, Permissions $permissionsModel) {
    $this->db = $db;
    $this->characterModel = $characterModel;
    $this->cache = $cache;
    $this->permissionsModel = $permissionsModel;
  }
  
  /**
   * Get name of specified race
   * 
   * @param int $id Race's id
   * @return string
   */
  function getRaceName($id) {
    $racesList = $this->characterModel->getRacesList();
    return $racesList[$id];
  }
  
  /**
   * Get name of specified class
   * 
   * @param int $id
   * @return string
   */
  function getClassName($id) {
    $classesList = $this->characterModel->getClassesList();
    return $classesList[$id];
  }
  
  /**
   * @return array
   */
  function getCharacters() {
    $return = array();
    $characters = $this->cache->load("characters");
    if($characters === NULL) {
      $characters = $this->db->table("characters");
      foreach($characters as $char) {
        $return[$char->id] = array(
          "id" => $char->id, "name" => $char->name
        );
      }
      $this->cache->save("characters", $return);
    } else {
      $return = $characters;
    }
    return $return;
  }
  
  /**
   * Get character's id
   * 
   * @param string $name Character's name
   * @return int
   */
  function getCharacterId($name) {
    $characters = $this->getCharacters();
    foreach($characters as $char) {
      if($char["name"] == $name) return $char["id"];
    }
    return 0;
  }
  
  /**
   * Get character's name
   * 
   * @param int $id Character's id
   * @return string
   */
  function getCharacterName($id) {
    $characters = $this->getCharacters();
    return $characters[$id]["name"];
  }
  
  /**
   * Get character's guild
   * 
   * @param string $id Character's id
   * @return int
   */
  function getCharacterGuild($id) {
    $char = $this->db->table("characters")->get($id);
    if(!$char) return 0;
    return $char->guild;
  }
  
  /**
   * Gets basic data about specified player
   * @param integer $id character's id
   * @return array info about character
   */
  function view($id) {
    $return = array();
    $char = $this->db->table("characters")->get($id);
    if(!$char) { return false; }
    $stats = array(
      "id", "name", "gender", "level", "race", "description", "strength", "dexterity",
      "constitution", "intelligence", "charisma", "race", "occupation", "specialization"
    );
    foreach($stats as $stat) {
      $return[$stat] = $char->$stat;
    }
    
    if($char->guild > 0) {
      $return["guild"] = $char->guild;
      $return["guildrank"] = $char->guildrank;
    } else {
      $return["guild"] = "";
    }
    $activePet = $this->db->table("pets")->where("owner=$char->id")->where("deployed=1");
    if($activePet->count() == 1) {
      $pet = $activePet->fetch();
      $petType = $this->db->table("pet_types")->get($pet->type);
      $petName = ($pet->name === NULL) ? "Unnamed" : $petName = $pet->name . ",";
      $return["pet"] = new Pet($id, $petType->id, $petName, $petType->bonus_stat, $petType->bonus_value);
    } else {
      $return["pet"] = false;
    }
    return $return;
  }
}
?>