<?php
namespace HeroesofAbenez;

/**
 * Data structure for stage
 * 
 * @author Jakub Konečný
 */
class Stage extends \Nette\Object {
  /** @var int id */
  public $id;
  /** @var string name */
  public $name;
  /** @var string description */
  public $description;
  /** @var int minimum level to enter stage */
  public $required_level;
  /** @var int id of race needed to enter stage */
  public $required_race;
  /** @var int id of class needed to enter stage */
  public $required_occupation;
  /** @var int id of parent area */
  public $area;
  /** @var int order in area */
  public $order;
  
  function __construct($id, $name, $description, $required_level, $required_race, $required_occupation, $area, $order) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->required_level = $required_level;
    $this->required_race = $required_race;
    $this->required_occupation = $required_occupation;
    $this->area = $area;
    $this->order = $order;
  }
}

/**
 * Data structure for area
 * 
 * @author Jakub Konečný
 */
class Area extends \Nette\Object {
  /** @var int id */
  public $id;
  /** @var string name */
  public $name;
  /** @var string description */
  public $description;
  /** @var int minimum level to enter stage */
  public $required_level;
  /** @var int id of race needed to enter stage */
  public $required_race;
  /** @var int id of class needed to enter stage */
  public $required_occupation;
  
  function __construct($id, $name, $description, $required_level, $required_race, $required_occupation) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->required_level = $required_level;
    $this->required_race = $required_race;
    $this->required_occupation = $required_occupation;
  }
}

/**
 * Location Model
 * 
 * @author Jakub Konečný
 */
class Location {
  /**
   * Gets list of stages
   * 
   * @param \Nette\Di\Container $container
   * @return array list of stages
   */
  static function listOfStages(\Nette\Di\Container $container) {
    $return = array();
    $cache = $container->getService("caches.locations");
    $stages = $cache->load("stages");
    if($stages === NULL) {
      $db = $container->getService("database.default.context");
      $stages = $db->table("quest_stages");
      foreach($stages as $stage) {
        $return[$stage->id] = new Stage($stage->id, $stage->name, $stage->description, $stage->required_level, $stage->required_race, $stage->required_occupation, $stage->area, $stage->order);
      }
      $cache->save("stages", $return);
    } else {
      $return = $stages;
    }
    return $return;
  }
  
  /**
   * Gets list of areas
   * 
   * @param \Nette\Di\Container $container
   * @return array list of stages
   */
  static function listOfAreas(\Nette\Di\Container $container) {
    $cache = $container->getService("caches.locations");
    $areas = $cache->load("areas");
    if($areas === NULL) {
      $db = $container->getService("database.default.context");
      $areas = $db->table("quest_areas");
      foreach($areas as $area) {
        $return[$area->id] = new Area($area->id, $area->name, $area->description, $area->required_level, $area->required_race, $area->required_occupation);
      }
      $cache->save("areas", $return);
    } else {
      $return = $areas;
    }
    return $return;
  }
  
  /**
   * Get name of specified stage
   * 
   * @param int $id Id of stage
   * @param \Nette\Di\Container $container
   */
  static function getStageName($id, \Nette\Di\Container $container) {
    $stages = Location::listOfStages($container);
    return $stages[$id]->name;
  }
  
  /**
   * Get name of specified area
   * 
   * @param int $id Id of area
   * @param \Nette\Di\Container $container
   */
  static function getAreaName($id, \Nette\Di\Container $container) {
    $areas = Location::listOfAreas($container);
    return $areas[$id]->name;
  }
  
  /**
   * Get data for homepage of location
   * 
   * @param int $location Id of stage
   * @param \Nette\Di\Container $container
   * @return array Data about location
   */
  static function Home($location, \Nette\Di\Container $container) {
    return Location::getStageName($location, $container);
  }
}
?>