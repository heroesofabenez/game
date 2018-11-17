<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\QuestArea;
use HeroesofAbenez\Orm\QuestStage;
use Nextras\Orm\Collection\ICollection;

require __DIR__ . "/../../bootstrap.php";

final class LocationTest extends \Tester\TestCase {
  /** @var Location */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Location::class);
  }
  
  public function testGetStage() {
    $stage = $this->model->getStage(1);
    Assert::type(QuestStage::class, $stage);
  }
  
  public function testStageRoutes() {
    $routes = $this->model->stageRoutes();
    Assert::type(ICollection::class, $routes);
  }
  
  public function testGetArea() {
    $stage = $this->model->getArea(1);
    Assert::type(QuestArea::class, $stage);
  }
  
  public function testGetStageName() {
    Assert::notSame("", $this->model->getStageName(1));
    Assert::same("", $this->model->getStageName(5000));
  }
  
  public function testGetAreaName() {
    Assert::notSame("", $this->model->getAreaName(1));
    Assert::same("", $this->model->getAreaName(5000));
  }
  
  public function testAccessibleStages() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    $result = $this->model->accessibleStages();
    Assert::type("array", $result);
    Assert::count(3, $result);
    foreach($result as $stage) {
      Assert::type(QuestStage::class, $stage);
    }
  }

  public function testCanEnterStage() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    /** @var QuestStage $stage */
    $stage = $this->model->getStage(1);
    Assert::true($this->model->canEnterStage($stage));
    $oldLevel = $stage->requiredLevel;
    $oldRace = $stage->requiredRace;
    $oldOccupation = $stage->requiredOccupation;
    $stage->requiredLevel = 999;
    Assert::false($this->model->canEnterStage($stage));
    $stage->requiredLevel = $oldLevel;
    $stage->requiredRace = 1;
    Assert::false($this->model->canEnterStage($stage));
    $stage->requiredRace = $oldRace;
    $stage->requiredOccupation = 1;
    Assert::false($this->model->canEnterStage($stage));
    $stage->requiredOccupation = $oldOccupation;
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    $orm->stages->persistAndFlush($stage);
  }

  public function testTravelToStage() {
    Assert::exception(function() {
      $this->model->travelToStage(5000);
    }, StageNotFoundException::class);
  }

  public function testCanEnterArea() {
    $this->model->user = $this->getService(\Nette\Security\User::class);
    /** @var QuestArea $area */
    $area = $this->model->getArea(1);
    Assert::true($this->model->canEnterArea($area));
    $oldLevel = $area->requiredLevel;
    $oldRace = $area->requiredRace;
    $oldOccupation = $area->requiredOccupation;
    $area->requiredLevel = 999;
    Assert::false($this->model->canEnterArea($area));
    $area->requiredLevel = $oldLevel;
    $area->requiredRace = 1;
    Assert::false($this->model->canEnterArea($area));
    $area->requiredRace = $oldRace;
    $area->requiredOccupation = 1;
    Assert::false($this->model->canEnterArea($area));
    $area->requiredOccupation = $oldOccupation;
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    $orm->areas->persistAndFlush($area);
  }

  public function testTravelToArea() {
    Assert::exception(function() {
      $this->model->travelToArea(5000);
    }, AreaNotFoundException::class);
  }
}

$test = new LocationTest();
$test->run();
?>