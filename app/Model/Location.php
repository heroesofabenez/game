<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\QuestAreaDummy,
    HeroesofAbenez\Orm\QuestStageDummy,
    HeroesofAbenez\Orm\QuestStage,
    HeroesofAbenez\Orm\QuestArea,
    HeroesofAbenez\Orm\RoutesStage;

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
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\Model\NPC */
  protected $npcModel;
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, ORM $orm) {
    $this->cache = $cache;
    $this->db = $db;
    $this->orm = $orm;
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
   * @return QuestStageDummy[] List of stages
   */
  function listOfStages(int $area = 0): array {
    $stages = $this->cache->load("stages", function(& $dependencies) {
      $return = [];
      $stages = $this->orm->stages->findAll();
      /** @var QuestStage $stage */
      foreach($stages as $stage) {
        $return[$stage->id] = new QuestStageDummy($stage);
      }
      return $return;
    });
    if($area > 0) {
      foreach($stages as $stage) {
        if($stage->area != $area) {
          unset($stages[$stage->id]);
        }
      }
    }
    return $stages;
  }
  
  /**
   * Gets data about specified stage
   * 
   * @param int $id Stage's id
   * @return QuestStageDummy|NULL
   */
  function getStage(int $id): ?QuestStageDummy {
    $stages = $this->listOfStages();
    return Arrays::get($stages, $id, NULL);
  }
  
  /**
   * Gets routes between stages
   * 
   * @return \stdClass[]
   */
  function stageRoutes(): array {
    $routes = $this->cache->load("stage_routes", function(& $dependencies) {
      $return = [];
      $routes = $this->orm->stageRoutes->findAll();
      /** @var RoutesStage $route */
      foreach($routes as $route) {
        $return[$route->id] = (object) ["from" => $route->from->id, "to" => $route->to->id];
      }
      return $return;
    });
    return $routes;
  }
  
  /**
   * Gets list of areas
   * 
   * @return QuestAreaDummy[] list of stages
   */
  function listOfAreas(): array {
    $areas = $this->cache->load("areas", function(& $dependencies) {
      $return = [];
      $areas = $this->orm->areas->findAll();
      /** @var QuestArea $area */
      foreach($areas as $area) {
        $return[$area->id] = new QuestAreaDummy($area);
      }
      return $return;
    });
    return $areas;
  }
  
  /**
   * Gets data about specified area
   * 
   * @param int $id Area's id
   * @return QuestAreaDummy|NULL
   */
  function getArea(int $id): ?QuestAreaDummy {
    $areas = $this->listOfAreas();
    return Arrays::get($areas, $id, NULL);
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
    if(is_null($area)) {
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
   * @return QuestStageDummy[]
   */
  function accessibleStages(): array {
    $stages = $this->listOfStages();
    $curr_stage = $stages[$this->user->identity->stage];
    /** @var QuestStage $stage */
    foreach($stages as $stage) {
      if($stage->area !== $curr_stage->area) {
        unset($stages[$stage->id]);
      }
      if($this->user->identity->level < $stage->requiredLevel) {
        unset($stages[$stage->id]);
      }
      if(is_int($stage->requiredRace) AND $stage->requiredRace != $this->user->identity->race) {
        unset($stages[$stage->id]);
      }
      if(is_int($stage->requiredOccupation) AND $stage->requiredOccupation != $this->user->identity->occupation) {
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