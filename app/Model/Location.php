<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\Area,
    HeroesofAbenez\Entities\Stage;

/**
 * Location Model
 * 
 * @author Jakub Konečný
 * @property-write \Nette\Security\User $user
 * @property-write NPC $npcModel
 */
class Location {
  use \Nette\SmartObject;
  
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
   * @return Stage[] List of stages
   */
  function listOfStages(int $area = 0): array {
    $return = [];
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
        if($stage->area != $area) {
          unset($return[$stage->id]);
        }
      }
    }
    return $return;
  }
  
  /**
   * Gets data about specified stage
   * 
   * @param int $id Stage's id
   * @return Stage
   */
  function getStage(int $id) {
    $stages = $this->listOfStages();
    $stage = Arrays::get($stages, $id, false);
    return $stage;
  }
  
  /**
   * Gets routes between stages
   * 
   * @return \stdClass[]
   */
  function stageRoutes(): array {
    $return = [];
    $routes = $this->cache->load("stage_routes");
    if($routes === NULL) {
      $routes = $this->db->table("routes_stages");
      foreach($routes as $route) {
        $return[$route->id] = (object) ["from" => $route->from, "to" => $route->to];
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
   * @return Area[] list of stages
   */
  function listOfAreas(): array {
    $areas = $this->cache->load("areas");
    if($areas === NULL) {
      $return = [];
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
   * @return Area
   */
  function getArea(int $id) {
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
  function getStageName(int $id): string {
    $stage = $this->getStage($id);
    if(!$stage) {
      return "";
    } else {
      return $stage->name;
    }
  }
  
  /**
   * Get name of specified area
   * 
   * @param int $id Id of area
   * @return string
   */
  function getAreaName(int $id): string {
    $area = $this->getArea($id);
    if(!$area) {
      return "";
    } else {
      return $area->name;
    }
  }
  
  /**
   * Get data for homepage
   * 
   * @return array Data about location
   */
  function home(): array {
    $return = [];
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
   * @return Stage[]
   */
  function accessibleStages(): array {
    $stages = $this->listOfStages();
    $curr_stage = $stages[$this->user->identity->stage];
    foreach($stages as $stage) {
      if($stage->area !== $curr_stage->area) {
        unset($stages[$stage->id]);
      }
      if($this->user->identity->level < $stage->required_level) {
        unset($stages[$stage->id]);
      }
      if(is_int($stage->required_race) AND $stage->required_race != $this->user->identity->race) {
        unset($stages[$stage->id]);
      }
      if(is_int($stage->required_occupation) AND $stage->required_occupation != $this->user->identity->occupation) {
        unset($stages[$stage->id]);
      }
    }
    return $stages;
  }
  
  /**
   * Try to travel to specified stage
   * 
   * @param int $id Stage's id
   * @return void
   * @throws StageNotFoundException
   * @throws CannotTravelToStageException
   */
  function travelToStage(int $id): void {
    $stage = $this->getStage($id);
    if(!$stage) {
      throw new StageNotFoundException;
    }
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
    if(!$foundRoute) {
      throw new CannotTravelToStageException;
    }
    $data = ["current_stage" => $id];
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $this->user->id);
  }
}
?>