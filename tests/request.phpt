<?php
declare(strict_types=1);

namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\Request;

class RequestModelTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\Request */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Request $model) {
    $this->model = $model;
  }

  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testCanShow(int $id) {
    $result = $this->model->canShow($id);
    Assert::true($result);
  }
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testShow(int $id) {
    $request = $this->model->show($id);
    Assert::type(Request::class, $request);
  }
}
?>