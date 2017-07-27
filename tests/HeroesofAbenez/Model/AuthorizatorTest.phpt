<?php
declare(strict_types=1);

namespace HeroesofAbenez\Tests;

use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

class AuthorizatorTest extends \Tester\TestCase {
  /** @var \Nette\Security\Permission */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(\Nette\Security\Permission::class);
  }
  
  /**
   * @return void
   */
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
  
  /**
   * @return void
   */
  public function testResources() {
    $resources = ["guild"];
    foreach($resources as $resource) {
      Assert::true($this->model->hasResource($resource));
    }
  }
    
  /**
   * @return void
   */
  public function testPermissions() {
    $resource = "guild";
    Assert::true($this->model->roleInheritsFrom("grandmaster", "guest"));
    Assert::false($this->model->isAllowed("guest", $resource));
    Assert::true($this->model->isAllowed("advisor", $resource, "invite"));
  }
}

$test = new AuthorizatorTest;
$test->run();
?>