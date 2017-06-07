<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\Pet as PetEntity,
    HeroesofAbenez\Orm\PetType;

require __DIR__ . "/../../bootstrap.php";

class PetTest extends \Tester\TestCase {
  /** @var Pet */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  function setUp() {
    $this->model = $this->getService(Pet::class);
  }
  
  /**
   * @return int[]
   */
  function getPetIds(): array {
    return [
      [1, 50,]
    ];
  }
  
  /**
   * @param int $id
   * @dataProvider getPetIds
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
   * @return int[]
   */
  function getUserIds(): array {
    return [
      [1, 2,]
    ];
  }
  
  /**
   * @param int $user
   * @dataProvider getUserIds
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

$test = new PetTest;
$test->run();
?>