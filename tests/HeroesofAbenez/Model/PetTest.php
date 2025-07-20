<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterClass;
use Tester\Assert;
use HeroesofAbenez\Orm\Pet as PetEntity;
use HeroesofAbenez\Orm\PetType;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class PetTest extends \Tester\TestCase {
  use TCharacterControl;

  private Pet $model;
  
  public function setUp(): void {
    $this->model = $this->getService(Pet::class); // @phpstan-ignore assign.propertyType
    $this->model->user = $this->getService(\Nette\Security\User::class); // @phpstan-ignore assign.propertyType
  }
  
  public function testViewType(): void {
    Assert::null($this->model->viewType(5000));
    /** @var PetType $pet */
    $pet = $this->model->viewType(1);
    Assert::type(PetType::class, $pet);
    Assert::same(1, $pet->id);
    Assert::same("Rescued Lion", $pet->name);
    Assert::same(PetType::STAT_CON, $pet->bonusStat);
    Assert::same(5, $pet->bonusValue);
    Assert::same(8, $pet->requiredLevel);
    Assert::type(CharacterClass::class, $pet->requiredClass);
    Assert::same(1, $pet->requiredClass?->id);
    Assert::null($pet->requiredRace);
    Assert::same(0, $pet->cost);
  }

  public function testCanDeployPet(): void {
    \Tester\Environment::lock("database", __DIR__ . "/../../..");
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    /** @var PetEntity $pet */
    $pet = $orm->pets->getById(1);
    $oldLevel = $pet->type->requiredLevel;
    $oldClass = $pet->type->requiredClass;
    $oldRace = $pet->type->requiredRace;
    $pet->type->requiredLevel = 1;
    Assert::true($this->model->canDeployPet($pet));
    $pet->type->requiredLevel = 999;
    Assert::false($this->model->canDeployPet($pet));
    $pet->type->requiredLevel = 1;
    $pet->type->requiredClass = 1;
    Assert::false($this->model->canDeployPet($pet));
    $pet->type->requiredClass = $oldClass;
    $pet->type->requiredRace = 1;
    Assert::false($this->model->canDeployPet($pet));
    $pet->type->requiredLevel = $oldLevel;
    $pet->type->requiredRace = $oldRace;
    $orm->petTypes->persistAndFlush($pet->type);
  }

  public function testDeployPetDiscardPet(): void {
    \Tester\Environment::lock("database", __DIR__ . "/../../..");
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    Assert::exception(function() {
      $this->model->deployPet(5000);
    }, PetNotFoundException::class);
    Assert::exception(function() {
      $this->model->discardPet(5000);
    }, PetNotFoundException::class);
    $pet = new PetEntity();
    $pet->type = $orm->petTypes->getById(8); // @phpstan-ignore assign.propertyType
    $pet->owner = $orm->characters->getById(2); // @phpstan-ignore assign.propertyType
    $orm->persistAndFlush($pet);
    Assert::exception(function() use ($pet) {
      $this->model->deployPet($pet->id);
    }, PetNotOwnedException::class);
    Assert::exception(function() use ($pet) {
      $this->model->discardPet($pet->id);
    }, PetNotOwnedException::class);
    $pet->owner = $this->getCharacter();
    $orm->persistAndFlush($pet);
    Assert::exception(function() use ($pet) {
      $this->model->deployPet($pet->id);
    }, PetNotDeployableException::class);
    Assert::exception(function() use ($pet) {
      $this->model->discardPet($pet->id);
    }, PetNotDeployedException::class);
    $this->modifyCharacter(["level" => $pet->type->requiredLevel, ], function() use ($pet) {
      $this->model->deployPet($pet->id);
      Assert::true($pet->deployed);
      Assert::exception(function() use ($pet) {
        $this->model->deployPet($pet->id);
      }, PetAlreadyDeployedException::class);
    });
    $this->model->discardPet($pet->id);
    Assert::false($pet->deployed);
    $orm->removeAndFlush($pet);
  }

  public function testGivePet(): void {
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
    /** @var PetEntity $pet */
    $pet = $orm->pets->getByTypeAndOwner(1, 1);
    Assert::type(PetEntity::class, $pet);
    $orm->pets->removeAndFlush($pet);
  }
}

$test = new PetTest();
$test->run();
?>