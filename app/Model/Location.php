<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\QuestStage;
use HeroesofAbenez\Orm\QuestArea;
use HeroesofAbenez\Orm\RoutesStage;
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
    }
    return $stage->name;
  }
  
  /**
   * Get name of specified area
   */
  public function getAreaName(int $id): string {
    $area = $this->getArea($id);
    if(is_null($area)) {
      return "";
    }
    return $area->name;
  }
  
  /**
   * Returns list of accessible stages (in player's current area)
   * 
   * @return QuestStage[]
   */
  public function accessibleStages(): array {
    $return = [];
    /** @var QuestStage $currentStage */
    $currentStage = $this->getStage($this->user->identity->stage);
    $stages = $this->orm->stages->findByArea($currentStage->area->id);
    foreach($stages as $stage) {
      $return[$stage->id] = $stage;
    }
    return $return;
  }

  public function canEnterStage(QuestStage $stage): bool {
    if($stage->requiredLevel > $this->user->identity->level) {
      return false;
    }
    if(!is_null($stage->requiredRace) AND $stage->requiredRace->id !== $this->user->identity->race) {
      return false;
    }
    if(!is_null($stage->requiredOccupation) AND $stage->requiredOccupation->id !== $this->user->identity->occupation) {
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
      if($route->from->id == $id AND $route->to->id == $currentStage) {
        $foundRoute = true;
        break;
      } elseif($route->from->id == $currentStage AND $route->to->id == $id) {
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
}
?>