<?php
declare(strict_types=1);

namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    Nette\Security\Identity;

class UserManagerTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\UserManager */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\UserManager $model) {
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