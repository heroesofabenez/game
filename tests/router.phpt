<?php
declare(strict_types=1);

namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    Nette\Application\IRouter,
    Nette\Application\Routers\Route;

class RouterTest extends MT\TestCase {
  /** @var \Nette\Application\Routers\RouteList */
  protected $router;
  
  function __construct(\Nette\Application\Routers\RouteList $router) {
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