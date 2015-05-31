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
class Location extends \Nette\Object {
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Database\Context */
  protected $db;
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->cache = $cache;
    $this->db = $db;
  }
  
  /**
   * Gets list of stages
   * 
   * @param
   * @return array list of stages
   */
  function listOfStages() {
    $return = array();
    $stages = $this->cache->load("stages");
    if($stages === NULL) {
      $stages = $this->db->table("quest_stages");
      foreach($stages as $stage) {
        $return[$stage->id] = new Stage($stage->id, $stage->name, $stage->description, $stage->required_level, $stage->required_race, $stage->required_occupation, $stage->area, $stage->order);
      }
      $this->cache->save("stages", $return);
    } else {
      $return = $stages;
    }
    return $return;
  }
  
  /**
   * Gets list of areas
   * 
   * @return array list of stages
   */
  function listOfAreas() {
    $areas = $this->cache->load("areas");
    if($areas === NULL) {
      $areas = $this->db->table("quest_areas");
      foreach($areas as $area) {
        $return[$area->id] = new Area($area->id, $area->name, $area->description, $area->required_level, $area->required_race, $area->required_occupation);
      }
      $this->cache->save("areas", $return);
    } else {
      $return = $areas;
    }
    return $return;
  }
  
  /**
   * Get name of specified stage
   * 
   * @param int $id Id of stage
   */
  function getStageName($id) {
    $stages = $this->listOfStages();
    return $stages[$id]->name;
  }
  
  /**
   * Get name of specified area
   * 
   * @param int $id Id of area
   */
  function getAreaName($id) {
    $areas = $this->listOfAreas();
    return $areas[$id]->name;
  }
  
  /**
   * Get data for homepage of location
   * 
   * @param int $location Id of stage
   * @return array Data about location
   */
  function Home($location) {
    return $this->getStageName($location);
  }
}
?>