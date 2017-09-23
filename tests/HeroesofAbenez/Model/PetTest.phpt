<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\Pet as PetEntity,
    HeroesofAbenez\Orm\PetType;

require __DIR__ . "/../../bootstrap.php";

final class PetTest extends \Tester\TestCase {
  /** @var Pet */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Pet::class);
    $this->model->user = $this->getService(\Nette\Security\User::class);
  }
  
  public function testViewType() {
    Assert::type(PetType::class, $this->model->viewType(1));
    Assert::null($this->model->viewType(5000));
  }
  
  public function testGetActivePet() {
    Assert::type(PetEntity::class, $this->model->getActivePet(1));
    Assert::null($this->model->getActivePet(5000));
  }
  
  public function testDeployPet() {
    Assert::exception(function() {
      $this->model->deployPet(5000);
    }, PetNotFoundException::class);
    Assert::exception(function() {
      $this->model->deployPet(1);
    }, PetAlreadyDeployedException::class);
  }
  
  public function testDiscardPet() {
    Assert::exception(function() {
      $this->model->discardPet(5000);
    }, PetNotFoundException::class);
  }
}

$test = new PetTest();
$test->run();
?>