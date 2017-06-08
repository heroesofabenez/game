<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    Nette\Security\Identity;

require __DIR__ . "/../../bootstrap.php";

/**
 * @testCase
 */
class UserManagerTest extends \Tester\TestCase {
  /** @var UserManager */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  function setUp() {
    $this->model = $this->getService(UserManager::class);
  }
  
  /**
   * @return void
   */
  function testAuthenticate() {
    $identity = $this->model->authenticate([]);
    Assert::type(Identity::class, $identity);
    Assert::same(1, $identity->id);
  }
}

$test = new UserManagerTest;
$test->run();
?>