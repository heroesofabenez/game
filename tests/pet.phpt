<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT;
use MyTester\Assert;

class PetTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\Pet */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Pet $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testListOfTypes() {
    $types = $this->model->listOfTypes();
    Assert::type("array", $types);
    Assert::type("HeroesofAbenez\Entities\PetType", $types[0]);
  }
  
  /**
   * @param int $id
   * @data(1,50)
   * @return void
   */
  function testViewType($id) {
    $type = $this->model->viewType($id);
    if($id === 1) {
      Assert::type("HeroesofAbenez\Entities\PetType", $type);
    } elseif($id === 50) {
      Assert::false($type);
    }
  }
  
  /**
   * @param int $user
   * @data(1,2)
   * @return void
   */
  function testGetActivePet($user) {
    $pet = $this->model->getActivePet($user);
    if($user === 1) {
      Assert::type("HeroesofAbenez\Entities\Pet", $pet);
      Assert::contains($pet->name, "Unnamed");
    } elseif($user === 2) {
      Assert::false($pet);
    }
  }
}
?>