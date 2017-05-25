<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\Pet as PetEntity,
    HeroesofAbenez\Orm\PetType;

class PetTest extends MT\TestCase {
  /** @var Pet */
  protected $model;
  
  function __construct(Pet $model) {
    $this->model = $model;
  }
  
  /**
   * @param int $id
   * @data(1,50)
   * @return void
   */
  function testViewType(int $id) {
    $type = $this->model->viewType($id);
    if($id === 1) {
      Assert::type(PetType::class, $type);
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
      Assert::null($pet->name);
    } elseif($user === 2) {
      Assert::null($pet);
    }
  }
}
?>