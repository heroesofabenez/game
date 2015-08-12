<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\CharacterClass,
    HeroesofAbenez\Entities\CharacterRace,
    Nette\Utils\Arrays;

/**
 * Model Character
 * 
 * @author Jakub Konečný
 */
class Character extends \Nette\Object {
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Database\Context */
  protected $db;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->cache = $cache;
    $this->db = $db;
  }
  
  /**
   * Get list of races
   * 
   * @return array
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
   * Get list of classes
   * 
   * @return array
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
   * Creates new character
   * 
   * @param \Nette\Utils\ArrayHash $values
   * @return array Stats of new character
   */
  function create($values) {
    $data = array(
      "name" => $values["name"], "race" => $values["race"],
      "occupation" => $values["class"], "gender" => $values["gender"]
    );
    $chars = $this->db->table("characters")->where("name", $data["name"]);
    if($chars->count() > 0) return false;
    
    $race = $this->getRace($values["race"]);
    $class = $this->getClass($values["class"]);
    $data["strength"] = $class->strength + $race->strength;
    $data["dexterity"] = $class->dexterity + $race->dexterity;
    $data["constitution"] = $class->constitution + $race->constitution;
    $data["intelligence"] = $class->intelligence + $race->intelligence;
    $data["charisma"] = $class->charisma + $race->charisma;
    $data["owner"] = UserManager::getRealId();
    $this->db->query("INSERT INTO characters", $data);
    
    $data["class"] = $values["class"];
    $data["race"] = $values["race"];
    if($data["gender"]  == 1) $data["gender"] = "male";
    else $data["gender"] = "female";
    unset($data["occupation"]);
    return $data;
  }
}
?>