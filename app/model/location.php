<?php
namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\Area,
    HeroesofAbenez\Entities\Stage;

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
  /** @var \HeroesofAbenez\Model\NPC */
  protected $npcModel;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->cache = $cache;
    $this->db = $db;
  }
  
  function setUser(\Nette\Security\User $user) {
    $this->user = $user;
  }
  
  function setNpcModel(NPC $npcModel) {
    $this->npcModel = $npcModel;
  }
  
  /**
   * Gets list of stages
   * 
   * @param int $area Return stages only from specified area. 0 = all areas
   * @return array list of stages
   */
  function listOfStages($area = 0) {
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
    if($area > 0) {
      foreach($return as $stage) {
        if($stage->area != $area) unset($return[$stage->id]);
      }
    }
    return $return;
  }
  
  /**
   * Gets data about specified stage
   * 
   * @param int $id Stage's id
   * @return \HeroesofAbenez\Entities\Stage
   */
  function getStage($id) {
    $stages = $this->listOfStages();
    $stage = Arrays::get($stages, $id, false);
    return $stage;
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
   * Gets data about specified area
   * 
   * @param int $id Area's id
   * @return HeroesofAbenez\Entities\Area
   */
  function getArea($id) {
    $areas = $this->listOfAreas();
    $area = Arrays::get($areas, $id, false);
    return $area;
  }
  
  /**
   * Get name of specified stage
   * 
   * @param int $id Id of stage
   * @return string
   */
  function getStageName($id) {
    $stage = $this->getStage($id);
    return $stage->name;
  }
  
  /**
   * Get name of specified area
   * 
   * @param int $id Id of area
   * @return string
   */
  function getAreaName($id) {
    $area = $this->getArea($id);
    return $area->name;
  }
  
  /**
   * Get data for homepage
   * 
   * @return array Data about location
   */
  function home() {
    $return = array();
    $stage = $this->getStage($this->user->identity->stage);
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
   * @return void
   * @throws \Nette\Application\BadRequestException
   * @throws \Nette\Application\ForbiddenRequestException
   */
  function travelToStage($id) {
    $stage = $this->getStage($id);
    if(!$stage) throw new \Nette\Application\BadRequestException;
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
    if(!$foundRoute) throw new \Nette\Application\ForbiddenRequestException;
    $data = array("current_stage" => $id);
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $this->user->id);
  }
}
?>