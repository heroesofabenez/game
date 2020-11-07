<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 * @testCase
 */
final class PermissionsTest extends \Tester\TestCase {
  /** @var Permissions */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Permissions::class);
  }
  
  public function testGetPermissions() {
    $permissions = $this->model->getPermissions();
    Assert::type("array", $permissions);
    Assert::type("array", $permissions[1]);
    Assert::type("string", $permissions[1]["role"]);
    Assert::type("string", $permissions[1]["action"]);
  }
  
  public function testGetRoles() {
    $roles = $this->model->getRoles();
    Assert::type("array", $roles);
    Assert::type("string", $roles[1]);
  }

  public function testGetRankId() {
    Assert::same(1, $this->model->getRankId("recruit"));
    Assert::null($this->model->getRankId("abc"));
  }
}

$test = new PermissionsTest();
$test->run();
?>