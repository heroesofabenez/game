<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\QuestArea,
    HeroesofAbenez\Orm\QuestStage,
    Nextras\Orm\Collection\ICollection;

require __DIR__ . "/../../bootstrap.php";

/**
 * @testCase
 */
class LocationTest extends \Tester\TestCase {
  /** @var Location */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Location::class);
  }
  
  /**
   * @return void
   */
  public function testGetStage() {
    $stage = $this->model->getStage(1);
    Assert::type(QuestStage::class, $stage);
  }
  
  /**
   * @return void
   */
  public function testStageRoutes() {
    $routes = $this->model->stageRoutes();
    Assert::type(ICollection::class, $routes);
  }
  
  /**
   * @return void
   */
  public function testGetArea() {
    $stage = $this->model->getArea(1);
    Assert::type(QuestArea::class, $stage);
  }
  
  /**
   * @return void
   */
  public function testGetStageName() {
    $name = $this->model->getStageName(1);
    Assert::type("string", $name);
  }
  
  /**
   * @return int[]
   */
  public function getAreaIds(): array {
    return [
      [0, 1,]
    ];
  }
  
  /**
   * @param int $id
   * @dataProvider getAreaIds
   */
  public function testGetAreaName(int $id) {
    $name = $this->model->getAreaName($id);
    Assert::type("string", $name);
  }
}

$test = new LocationTest;
$test->run();
?>