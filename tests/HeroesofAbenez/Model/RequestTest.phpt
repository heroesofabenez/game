<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\Request as RequestEntity;

require __DIR__ . "/../../bootstrap.php";

class RequestTest extends \Tester\TestCase {
  /** @var Request */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  function setUp() {
    $this->model = $this->getService(Request::class);
  }
  
  /**
   * @return void
   */
  function testCanShow() {
    $result = $this->model->canShow(1);
    Assert::true($result);
  }
  /**
   * @return void
   */
  function testShow() {
    $request = $this->model->show(1);
    Assert::type(RequestEntity::class, $request);
  }
}

$test = new RequestTest;
$test->run();
?>