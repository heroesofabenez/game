<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\QuestStage,
    HeroesofAbenez\Orm\QuestArea,
    HeroesofAbenez\Orm\RoutesStage,
    Nextras\Orm\Collection\ICollection;

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
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\Model\NPC */
  protected $npcModel;
  
  public function __construct(\Nette\Caching\Cache $cache, ORM $orm) {
    $this->cache = $cache;
    $this->orm = $orm;
  }
  
  public function setUser(\Nette\Security\User $user) {
    $this->user = $user;
  }
  
  public function setNpcModel(NPC $npcModel) {
    $this->npcModel = $npcModel;
  }
  
  /**
   * Gets data about specified stage
   */
  public function getStage(int $id): ?QuestStage {
    return $this->orm->stages->getById($id);
  }
  
  /**
   * Gets routes between stages
   * 
   * @return ICollection|RoutesStage[]
   */
  public function stageRoutes(): ICollection {
    return $this->orm->stageRoutes->findAll();
  }
  
  /**
   * Gets data about specified area
   */
  public function getArea(int $id): ?QuestArea {
    return $this->orm->areas->getById($id);
  }
  
  /**
   * Get name of specified stage
   */
  public function getStageName(int $id): string {
    $stage = $this->getStage($id);
    if(is_null($stage)) {
      return "";
    } else {
      return $stage->name;
    }
  }
  
  /**
   * Get name of specified area
   */
  public function getAreaName(int $id): string {
    $area = $this->getArea($id);
    if(is_null($area)) {
      return "";
    } else {
      return $area->name;
    }
  }
  
  /**
   * Returns list of accessible stages (in player's current area,
   * player meets conditions for entering them)
   * 
   * @return QuestStage[]
   */
  public function accessibleStages(): array {
    $return = [];
    $stages = $this->orm->stages->findAll();
    /** @var QuestStage $curr_stage */
    $curr_stage = $this->getStage($this->user->identity->stage);
    foreach($stages as $stage) {
      if($stage->area->id !== $curr_stage->area->id) {
        continue;
      } elseif($this->user->identity->level < $stage->requiredLevel) {
        continue;
      } elseif($stage->requiredRace AND $stage->requiredRace->id != $this->user->identity->race) {
        continue;
      } elseif($stage->requiredOccupation AND $stage->requiredOccupation->id != $this->user->identity->occupation) {
        continue;
      }
      $return[$stage->id] = $stage;
    }
    return $return;
  }
  
  /**
   * Try to travel to specified stage
   *
   * @throws StageNotFoundException
   * @throws CannotTravelToStageException
   */
  public function travelToStage(int $id): void {
    $stage = $this->getStage($id);
    if(is_null($stage)) {
      throw new StageNotFoundException;
    }
    $currentStage = $this->user->identity->stage;
    $foundRoute = false;
    $routes = $this->stageRoutes();
    foreach($routes as $route) {
      if($route->from->id == $id AND $route->to->id == $currentStage) {
        $foundRoute = true;
        break;
      } elseif($route->from->id == $currentStage AND $route->to->id == $id) {
        $foundRoute = true;
        break;
      }
    }
    if(!$foundRoute) {
      throw new CannotTravelToStageException;
    }
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $character->currentStage = $id;
    $this->orm->characters->persistAndFlush($character);
  }
}
?>