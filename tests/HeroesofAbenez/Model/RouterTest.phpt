<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    Nette\Application\IRouter,
    Nette\Application\Routers\Route,
    Nette\Application\Routers\RouteList;

require __DIR__ . "/../../bootstrap.php";

/**
 * @testCase
 */
class RouterTest extends \Tester\TestCase {
  /** @var RouteList */
  protected $router;
  
  use \Testbench\TCompiledContainer;
  
  function setUp() {
    $this->router = $this->getService(RouteList::class);
  }
  
  /**
   * @return void
   */
  function testRoutes() {
    foreach($this->router->getIterator() as $route) {
      Assert::type(IRouter::class, $route);
      Assert::type(Route::class, $route);
    }
  }
}

$test = new RouterTest;
$test->run();
?>