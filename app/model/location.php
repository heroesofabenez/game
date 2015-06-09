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
  public $pos_x;
  /** @var int */
  public $pos_y;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->name != "quest_stages") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
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
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->name != "quest_areas") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
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
        $return[$stage->id] = new Stage($stage);
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
        $return[$area->id] = new Area($area);
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
  
  /**
   * Returns list of accessible stages (in player's current area,
   * player meets conditions for entering them)
   * 
   * @return array
   */
  function accessibleStages() {
    $stages = $this->listOfStages();
    $curr_stage = $stages[$this->user->identity->stage];
    foreach($stages as $stage) {
      if($stage->area !== $curr_stage->area) unset($stages[$stage->id]);
      if($this->user->identity->level < $stage->required_level) unset($stages[$stage->id]);
      if(is_int($stage->required_race) AND $stage->required_race != $this->user->identity->race) unset($stages[$stage->id]);
      if(is_int($stage->required_occupation) AND $stage->required_occupation != $this->user->identity->occupation) unset($stages[$stage->id]);
    }
    return $stages;
  }
  
  /**
   * Try to travel to specified stage
   * 
   * @param int $id Stage's id
   * @return int Error code|1 on success
   */
  function travelToStage($id) {
    $stages = $this->listOfStages();
    if(!isset($stages[$id])) return 2;
    $currentStage = $this->user->identity->stage;
    $foundRoute = false;
    $routes = $this->stageRoutes();
    foreach($routes as $route) {
      if($route->from == $id AND $route->to == $currentStage) {
        $foundRoute = true;
        break;
      } elseif($route->from == $currentStage AND $route->to == $id) {
        $foundRoute = true;
        break;
      }
    }
    if(!$foundRoute) return 3;
    $data = array("current_stage" => $id);
    $result = $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $this->user->id);
    if($result) return 1;
    else return 4;
  }
}
?>