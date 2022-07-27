<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class RouterTest extends \Tester\TestCase {
  private RouteList $router;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->router = $this->getService(RouteList::class);
  }
  
  public function testRoutes() {
    foreach($this->router->getRouters() as $route) {
      Assert::type(Route::class, $route);
    }
  }
}

$test = new RouterTest();
$test->run();
?>