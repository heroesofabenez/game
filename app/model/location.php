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
  /** @var int */
  public $x;
  /** @var int */
  public $y;
  
  function __construct($id, $name, $description, $required_level, $required_race, $required_occupation, $area, $x, $y) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->required_level = $required_level;
    $this->required_race = $required_race;
    $this->required_occupation = $required_occupation;
    $this->area = $area;
    if(is_int($x)) $this->x = $x;
    if(is_int($y)) $this->y = $y;
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
  
  /**
   * @param int $id
   * @param string $name
   * @param string $description
   * @param int $required_level
   * @param int $required_race
   * @param int $required_occupation
   */
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
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\NPCModel */
  protected $npcModel;
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->cache = $cache;
    $this->db = $db;
  }
  
  function setUser(\Nette\Security\User $user) {
    $this->user = $user;
  }
  
  function setNpcModel(\HeroesofAbenez\NPCModel $npcModel) {
    $this->npcModel = $npcModel;
  }
  
  /**
   * Gets list of stages
   * 
   * @return array list of stages
   */
  function listOfStages() {
    $return = array();
    $stages = $this->cache->load("stages");
    if($stages === NULL) {
      $stages = $this->db->table("quest_stages");
      foreach($stages as $stage) {
        $return[$stage->id] = new Stage($stage->id, $stage->name, $stage->description, $stage->required_level, $stage->required_race, $stage->required_occupation, $stage->area, $stage->pos_x, $stage->pos_y);
      }
      $this->cache->save("stages", $return);
    } else {
      $return = $stages;
    }
    return $return;
  }
  
  /**
   * Gets routes between stages
   * 
   * @return array
   */
  function stageRoutes() {
    $return = array();
    $routes = $this->cache->load("stage_routes");
    if($routes === NULL) {
      $routes = $this->db->table("routes_stages");
      foreach($routes as $route) {
        $return[$route->id] = (object) array("from" => $route->from, "to" => $route->to);
      }
      $this->cache->save("stage_routes", $return);
    } else {
      $return = $routes;
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
   * @return string
   */
  function getStageName($id) {
    $stages = $this->listOfStages();
    return $stages[$id]->name;
  }
  
  /**
   * Get name of specified area
   * 
   * @param int $id Id of area
   * @return string
   */
  function getAreaName($id) {
    $areas = $this->listOfAreas();
    return $areas[$id]->name;
  }
  
  /**
   * Get data for homepage
   * 
   * @return array Data about location
   */
  function home() {
    $return = array();
    $stages = $this->listOfStages();
    $stage = $stages[$this->user->identity->stage];
    $return["stageName"] = $stage->name;
    $return["areaName"] = $this->getAreaName($stage->area);
    $return["characterName"] = $this->user->identity->name;
    $return["npcs"] = $this->npcModel->listOfNpcs($stage->id);
    return $return;
  }
}
?>