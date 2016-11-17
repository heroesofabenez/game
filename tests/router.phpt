<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    Nette\Application\IRouter,
    Nette\Application\Routers\Route,
    Nette\Application\Routers\RouteList;

class RouterTest extends MT\TestCase {
  /** @var RouteList */
  protected $router;
  
  function __construct(RouteList $router) {
    $this->router = $router;
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
?>