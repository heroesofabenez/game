<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Pet as PetEntity;

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
  /** @var \HeroesofAbenez\Model\Permissions */
  protected $permissionsModel;
  /** @var \HeroesofAbenez\Model\Pet */
  protected $petModel;
  
  function __construct(\Nette\Database\Context $db, \Nette\Caching\Cache $cache, Permissions $permissionsModel, Pet $petModel) {
    $this->db = $db;
    $this->cache = $cache;
    $this->permissionsModel = $permissionsModel;
    $this->petModel = $petModel;
  }
  
  /**
   * Get list of races
   * 
   * @return CharacterRace[]
   */
  function getRacesList() {
    $racesList = $this->cache->load("races");
    if($racesList === NULL) {
      $racesList = array();
      $races = $this->db->table("character_races");
      foreach($races as $race) {
        $racesList[$race->id] = new CharacterRace($race);
      }
      $this->cache->save("races", $racesList);
    }
    return $racesList;
  }
  
  /**
   * Get data about specified race
   * 
   * @param int $id Race's id
   * @return CharacterRace|bool
   */
  function getRace($id) {
    $races = $this->getRacesList();
    $race = Arrays::get($races, $id, false);
    return $race;
  }
  
  /**
   * Get name of specified race
   * 
   * @param int $id Race's id
   * @return string
   */
  function getRaceName($id) {
    $racesList = $this->getRacesList();
    return $racesList[$id]->name;
  }
  
  /**
   * Get list of classes
   * 
   * @return CharacterClass[]
   */
  function getClassesList() {
    $classesList = $this->cache->load("classes");
    if($classesList === NULL) {
      $classesList = array();
      $classes = $this->db->table("character_classess");
      foreach($classes as $class) {
        $classesList[$class->id] = new CharacterClass($class);
      }
      $this->cache->save("classes", $classesList);
    }
    return $classesList;
  }
  
  /**
   * Get data about specified class
   * 
   * @param int $id Class' id
   * @return CharacterClass|bool
   */
  function getClass($id) {
    $classes = $this->getClassesList();
    $class = Arrays::get($classes, $id, false);
    return $class;
  }
  
  /**
   * Get name of specified class
   * 
   * @param int $id
   * @return string
   */
  function getClassName($id) {
    $classesList = $this->getClassesList();
    return $classesList[$id]->name;
  }
  
  /**
   * @return \stdClass[]
   */
  function getCharacters() {
    $return = array();
    $stats = array("id", "name");
    $characters = $this->cache->load("characters");
    if($characters === NULL) {
      $characters = $this->db->table("characters");
      foreach($characters as $character) {
        $char = new \stdClass;
        foreach($stats as $stat) {
          $char->$stat = $character->$stat;
        }
        $return[$character->id] = $char;
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
      if($char->name == $name) return $char["id"];
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
    return $characters[$id]->name;
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
   * 
   * @param integer $id character's id
   * @return array info about character
   */
  function view($id) {
    $return = array();
    $char = $this->db->table("characters")->get($id);
    if(!$char) { return false; }
    $stats = array(
      "id", "name", "gender", "level", "race", "description", "strength", "dexterity",
      "constitution", "intelligence", "charisma", "occupation", "specialization"
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
      $petType = $this->petModel->viewType($pet->type);
      $petName = ($pet->name === NULL) ? "Unnamed" : $petName = $pet->name . ",";
      $return["pet"] = new PetEntity($id, $petType, $petName);
    } else {
      $return["pet"] = false;
    }
    return $return;
  }
}
?>