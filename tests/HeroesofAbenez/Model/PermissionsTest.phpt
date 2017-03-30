<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert;

class PermissionsTest extends MT\TestCase {
  /** @var Permissions */
  protected $model;
  
  function __construct(Permissions $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testGetPermissions() {
    $permissions = $this->model->getPermissions();
    Assert::type("array", $permissions);
    Assert::type("array", $permissions[1]);
    Assert::type("string", $permissions[1]["role"]);
    Assert::type("string", $permissions[1]["action"]);
  }
  
  /**
   * @return void
   */
  function testGetRoles() {
    $roles = $this->model->getRoles();
    Assert::type("array", $roles);
    Assert::type("array", $roles[1]);
    Assert::type("int", $roles[1]["id"]);
    Assert::type("string", $roles[1]["name"]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetRoleName(int $id) {
    $name = $this->model->getRoleName($id);
    Assert::type("string", $name);
  }
}
?>