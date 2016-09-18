<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert;

class AuthorizatorTest extends MT\TestCase {
  /** @var \Nette\Security\Permission */
  protected $model;
  
  function __construct(\Nette\Security\Permission $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testRoles() {
    $roles = ["guest", "player", "recruit", "member", "regular", "advisor", "master", "deputy", "grandmaster"];
    foreach($roles as $role) {
      Assert::true($this->model->hasRole($role));
      $parents = $this->model->getRoleParents($role);
      if($role === "guest") {
        Assert::false(count($parents));
      } elseif($role === "player") {
        Assert::true(count($parents));
        Assert::true($this->model->roleInheritsFrom($role, "guest"));
      } else {
        Assert::true(count($parents));
        Assert::true($this->model->roleInheritsFrom($role, "player"));
      }
    }
  }
  
  /**
   * @return void
   */
  function testResources() {
    $resources = ["guild"];
    foreach($resources as $resource) {
      Assert::true($this->model->hasResource($resource));
    }
  }
    
  /**
   * @return void
   */
  function testPermissions() {
    $resource = "guild";
    Assert::true($this->model->roleInheritsFrom("grandmaster", "guest"));
    Assert::false($this->model->isAllowed("guest", $resource));
    Assert::true($this->model->isAllowed("advisor", $resource, "invite"));
  }
}
?>