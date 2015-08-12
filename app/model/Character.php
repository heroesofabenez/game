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
}
?>