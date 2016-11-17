<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    Nette\Security\Identity;

class UserManagerTest extends MT\TestCase {
  /** @var UserManager */
  protected $model;
  
  function __construct(UserManager $model) {
    $this->model = $model;
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
?>