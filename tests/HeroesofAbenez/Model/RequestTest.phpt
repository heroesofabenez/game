<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\Request as RequestEntity;

class RequestTest extends MT\TestCase {
  /** @var Request */
  protected $model;
  
  function __construct(Request $model) {
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
    Assert::type(RequestEntity::class, $request);
  }
}
?>