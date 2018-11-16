<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\Pet as PetEntity;
use HeroesofAbenez\Orm\PetType;

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

  public function testGivePet() {
    Assert::noError(function() {
      $this->model->givePet(5000);
    });
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    $oldPetCount = $orm->pets->findAll()->countStored();
    $this->model->givePet(3);
    $newPetCount = $orm->pets->findAll()->countStored();
    Assert::same($oldPetCount, $newPetCount);
    $this->model->givePet(1);
    $pet = $orm->pets->getByTypeAndOwner(1, 1);
    Assert::type(PetEntity::class, $pet);
    $orm->pets->removeAndFlush($pet);
  }
}

$test = new PetTest();
$test->run();
?>