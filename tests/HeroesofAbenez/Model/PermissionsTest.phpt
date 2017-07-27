<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

/**
 * @testCase
 */
class PermissionsTest extends \Tester\TestCase {
  /** @var Permissions */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Permissions::class);
  }
  
  /**
   * @return void
   */
  public function testGetPermissions() {
    $permissions = $this->model->getPermissions();
    Assert::type("array", $permissions);
    Assert::type("array", $permissions[1]);
    Assert::type("string", $permissions[1]["role"]);
    Assert::type("string", $permissions[1]["action"]);
  }
  
  /**
   * @return void
   */
  public function testGetRoles() {
    $roles = $this->model->getRoles();
    Assert::type("array", $roles);
    Assert::type("array", $roles[1]);
    Assert::type("int", $roles[1]["id"]);
    Assert::type("string", $roles[1]["name"]);
  }
  
  /**
   * @return void
   */
  public function testGetRoleName() {
    $name = $this->model->getRoleName(1);
    Assert::type("string", $name);
  }
}

$test = new PermissionsTest;
$test->run();
?>