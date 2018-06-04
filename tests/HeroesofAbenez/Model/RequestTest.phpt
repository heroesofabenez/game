<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\Request as RequestEntity;

require __DIR__ . "/../../bootstrap.php";

final class RequestTest extends \Tester\TestCase {
  /** @var Request */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Request::class);
  }
  
  public function testCanShow() {
    $result = $this->model->canShow(1);
    Assert::true($result);
  }
  
  public function testShow() {
    $request = $this->model->show(1);
    Assert::type(RequestEntity::class, $request);
    Assert::exception(function() {
      $this->model->show(5000);
    }, RequestNotFoundException::class);
  }
}

$test = new RequestTest();
$test->run();
?>