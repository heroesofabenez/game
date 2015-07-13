<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Presenters\BasePresenter;

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
        $racesList[$race->id] = $race->name;
      }
      $this->cache->save("races", $racesList);
    }
    return $racesList;
  }
  
  /**
   * Get description of races
   * 
   * @return array
   */
  function getRacesDescriptions() {
    $return = array();
    $races = $this->db->table("character_races");
    foreach($races as $race) {
      $return[$race->id] = $race->description;
    }
    return $return;
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
        $classesList[$class->id] = $class->name;
      }
      $this->cache->save("classes", $classesList);
    }
    return $classesList;
  }
  
  /**
   * Get description of classes
   * 
   * @return array
   */
  function getClassesDescriptions() {
    $return = array();
    $classes = $this->db->table("character_classess");
    foreach($classes as $class) {
      $return[$class->id] = $class->description;
    }
    return $return;
  }
  
  /**
   * Creates new character
   * 
   * @param type $values
   */
  function create($values) {
    $data = array(
      "name" => $values["name"], "race" => $values["race"],
      "occupation" => $values["class"], "gender" => $values["gender"]
    );
    $chars = $this->db->table("characters")->where("name", $data["name"]);
    if($chars->count() > 0) return false;
    
    $race = $this->db->table("character_races")->get($values["race"]);
    $class = $this->db->table("character_classess")->get($values["class"]);
    $data["strength"] = $class->strength + $race->strength;
    $data["dexterity"] = $class->dexterity + $race->dexterity;
    $data["constitution"] = $class->constitution + $race->constitution;
    $data["intelligence"] = $class->intelligence + $race->intelligence;
    $data["charisma"] = $class->charisma + $race->charisma;
    $data["owner"] = BasePresenter::getRealId();
    $this->db->query("INSERT INTO characters", $data);
    
    $data["class"] = $class->name;
    $data["race"] = $race->name;
    if($data["gender"]  == 1) $data["gender"] = "male";
    else $data["gender"] = "female";
    unset($data["occupation"]);
    return $data;
  }
}
?>