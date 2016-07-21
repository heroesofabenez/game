<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT;
use MyTester\Assert;

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
  function testCanShow($id) {
    $result = $this->model->canShow($id);
    Assert::true($result);
  }
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testShow($id) {
    $request = $this->model->show($id);
    Assert::type("\HeroesofAbenez\Entities\Request", $request);
  }
}

/*$suit = new RequestModelTest($container->getService("hoa.model.request"));
$suit->run();*/
?>