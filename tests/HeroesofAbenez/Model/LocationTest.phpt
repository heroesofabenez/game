<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\QuestAreaDummy,
    HeroesofAbenez\Orm\QuestStageDummy;

class LocationTest extends MT\TestCase {
  /** @var Location */
  protected $model;
  
  function __construct(Location $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testListOfStages() {
    $stages = $this->model->listOfStages();
    Assert::type("array", $stages);
    Assert::type(QuestStageDummy::class, $stages[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   */
  function testGetStage(int $id) {
    $stage = $this->model->getStage($id);
    Assert::type(QuestStageDummy::class, $stage);
  }
  
  /**
   * @return void
   */
  function testStageRoutes() {
    $routes = $this->model->stageRoutes();
    Assert::type("array", $routes);
    Assert::type("stdClass", $routes[1]);
    Assert::type("int", $routes[1]->from);
    Assert::type("int", $routes[1]->to);
  }
  
  /**
   * @return void
   */
  function testListOfAreas() {
    $areas = $this->model->listOfAreas();
    Assert::type("array", $areas);
    Assert::type(QuestAreaDummy::class, $areas[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   */
  function testGetArea(int $id) {
    $stage = $this->model->getArea($id);
    Assert::type(QuestAreaDummy::class, $stage);
  }
  
  /**
   * @param int $id
   * @data(0,1)
   */
  function testGetStageName(int $id) {
    $name = $this->model->getStageName($id);
    Assert::type("string", $name);
  }
  
  /**
   * @param int $id
   * @data(0,1)
   */
  function testGetAreaName(int $id) {
    $name = $this->model->getAreaName($id);
    Assert::type("string", $name);
  }
}
?>