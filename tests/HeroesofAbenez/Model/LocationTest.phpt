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
}

$test = new LocationTest();
$test->run();
?>