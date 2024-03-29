<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class AuthorizatorFactoryTest extends \Tester\TestCase {
  private \Nette\Security\Permission $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(\Nette\Security\Permission::class);
  }
  
  public function testRoles() {
    $roles = ["guest", "player", "recruit", "member", "regular", "advisor", "master", "deputy", "grandmaster"];
    foreach($roles as $role) {
      Assert::true($this->model->hasRole($role));
      $parents = $this->model->getRoleParents($role);
      if($role === "guest") {
        Assert::count(0, $parents);
      } elseif($role === "player") {
        Assert::true(count($parents) > 0);
        Assert::true($this->model->roleInheritsFrom($role, "guest"));
      } else {
        Assert::true(count($parents) > 0);
        Assert::true($this->model->roleInheritsFrom($role, "player"));
      }
    }
  }
  
  public function testResources() {
    $resources = ["guild"];
    foreach($resources as $resource) {
      Assert::true($this->model->hasResource($resource));
    }
  }
  
  public function testPermissions() {
    $resource = "guild";
    Assert::true($this->model->roleInheritsFrom("grandmaster", "guest"));
    Assert::false($this->model->isAllowed("guest", $resource));
    Assert::true($this->model->isAllowed("advisor", $resource, "invite"));
  }
}

$test = new AuthorizatorFactoryTest();
$test->run();
?>