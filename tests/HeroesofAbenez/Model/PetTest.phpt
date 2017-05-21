<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\Pet as PetEntity,
    HeroesofAbenez\Orm\PetTypeDummy;

class PetTest extends MT\TestCase {
  /** @var Pet */
  protected $model;
  
  function __construct(Pet $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testListOfTypes() {
    $types = $this->model->listOfTypes();
    Assert::type("array", $types);
    Assert::type(PetTypeDummy::class, $types[1]);
  }
  
  /**
   * @param int $id
   * @data(1,50)
   * @return void
   */
  function testViewType(int $id) {
    $type = $this->model->viewType($id);
    if($id === 1) {
      Assert::type(PetTypeDummy::class, $type);
    } elseif($id === 50) {
      Assert::null($type);
    }
  }
  
  /**
   * @param int $user
   * @data(1,2)
   * @return void
   */
  function testGetActivePet(int $user) {
    $pet = $this->model->getActivePet($user);
    if($user === 1) {
      Assert::type(PetEntity::class, $pet);
      Assert::contains($pet->name, "Unnamed");
    } elseif($user === 2) {
      Assert::null($pet);
    }
  }
}
?>