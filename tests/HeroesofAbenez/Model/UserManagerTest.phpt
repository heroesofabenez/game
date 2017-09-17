<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    Nette\Security\Identity;

require __DIR__ . "/../../bootstrap.php";

final class UserManagerTest extends \Tester\TestCase {
  /** @var UserManager */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(UserManager::class);
  }
  
  public function testAuthenticate() {
    $identity = $this->model->authenticate([]);
    Assert::type(Identity::class, $identity);
    Assert::same(1, $identity->id);
  }
}

$test = new UserManagerTest;
$test->run();
?>