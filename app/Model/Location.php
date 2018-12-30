<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\QuestStage;
use HeroesofAbenez\Orm\QuestArea;
use HeroesofAbenez\Orm\RoutesStage;
use HeroesofAbenez\Orm\RoutesArea;
use Nextras\Orm\Collection\ICollection;

/**
 * Location Model
 * 
 * @author Jakub Konečný
 * @property-write \Nette\Security\User $user
 * @property-write NPC $npcModel
 */
final class Location {
  use \Nette\SmartObject;

  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \HeroesofAbenez\Model\NPC */
  protected $npcModel;
  
  public function __construct(ORM $orm) {
    $this->orm = $orm;
  }
  
  public function setUser(\Nette\Security\User $user): void {
    $this->user = $user;
  }
  
  public function setNpcModel(NPC $npcModel): void {
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
   * Gets routes between areas
   *
   * @return ICollection|RoutesArea[]
   */
  public function areaRoutes(): ICollection {
    return $this->orm->areaRoutes->findAll();
  }
  
  /**
   * Gets data about specified area
   */
  public function getArea(int $id): ?QuestArea {
    return $this->orm->areas->getById($id);
  }
  
  /**
   * Returns list of accessible stages (in player's current area)
   * 
   * @return QuestStage[]
   */
  public function accessibleStages(): array {
    /** @var QuestStage $currentStage */
    $currentStage = $this->getStage($this->user->identity->stage);
    $stages = $this->orm->stages->findByArea($currentStage->area->id);
    return $stages->fetchPairs("id", null);
  }

  public function canEnterStage(QuestStage $stage): bool {
    if($stage->requiredLevel > $this->user->identity->level) {
      return false;
    }
    if(!is_null($stage->requiredRace) AND $stage->requiredRace->id !== $this->user->identity->race) {
      return false;
    }
    if(!is_null($stage->requiredClass) AND $stage->requiredClass->id !== $this->user->identity->class) {
      return false;
    }
    return true;
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
      throw new StageNotFoundException();
    }
    $currentStage = $this->user->identity->stage;
    $foundRoute = false;
    $routes = $this->stageRoutes();
    foreach($routes as $route) {
      if($route->from->id === $id AND $route->to->id === $currentStage) {
        $foundRoute = true;
        break;
      } elseif($route->from->id === $currentStage AND $route->to->id === $id) {
        $foundRoute = true;
        break;
      }
    }
    if(!$foundRoute OR !$this->canEnterStage($stage)) {
      throw new CannotTravelToStageException();
    }
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $character->currentStage = $id;
    $this->orm->characters->persistAndFlush($character);
  }

  public function canEnterArea(QuestArea $area): bool {
    if($area->requiredLevel > $this->user->identity->level) {
      return false;
    }
    if(!is_null($area->requiredRace) AND $area->requiredRace->id !== $this->user->identity->race) {
      return false;
    }
    if(!is_null($area->requiredClass) AND $area->requiredClass->id !== $this->user->identity->class) {
      return false;
    }
    return true;
  }

  /**
   * Try to travel to specified area
   *
   * @throws AreaNotFoundException
   * @throws CannotTravelToAreaException
   */
  public function travelToArea(int $id): void {
    $area = $this->getArea($id);
    if(is_null($area)) {
      throw new AreaNotFoundException();
    }
    /** @var QuestStage $currentStage */
    $currentStage = $this->orm->stages->getById($this->user->identity->stage);
    $currentArea = $currentStage->area->id;
    $foundRoute = false;
    $routes = $this->areaRoutes();
    foreach($routes as $route) {
      if($route->from->id === $id AND $route->to->id === $currentArea) {
        $foundRoute = true;
        break;
      } elseif($route->from->id === $currentArea AND $route->to->id === $id) {
        $foundRoute = true;
        break;
      }
    }
    if(!$foundRoute OR !$this->canEnterArea($area) OR is_null($area->entryStage)) {
      throw new CannotTravelToAreaException();
    }
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $character->currentStage = $area->entryStage->id;
    $this->orm->characters->persistAndFlush($character);
  }
}
?>