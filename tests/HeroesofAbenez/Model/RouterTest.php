<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class RouterTest extends \Tester\TestCase {
  private RouteList $router;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp(): void {
    $this->router = $this->getService(RouteList::class); // @phpstan-ignore assign.propertyType
  }
  
  public function testRoutes(): void {
    foreach($this->router->getRouters() as $route) {
      Assert::type(Route::class, $route);
    }
  }
}

$test = new RouterTest();
$test->run();
?>