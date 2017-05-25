<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\QuestArea,
    HeroesofAbenez\Orm\QuestStage,
    Nextras\Orm\Collection\ICollection;

class LocationTest extends MT\TestCase {
  /** @var Location */
  protected $model;
  
  function __construct(Location $model) {
    $this->model = $model;
  }
  
  /**
   * @param int $id
   * @data(1)
   */
  function testGetStage(int $id) {
    $stage = $this->model->getStage($id);
    Assert::type(QuestStage::class, $stage);
  }
  
  /**
   * @return void
   */
  function testStageRoutes() {
    $routes = $this->model->stageRoutes();
    Assert::type(ICollection::class, $routes);
  }
  
  /**
   * @param int $id
   * @data(1)
   */
  function testGetArea(int $id) {
    $stage = $this->model->getArea($id);
    Assert::type(QuestArea::class, $stage);
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