<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\Pet as PetEntity,
    HeroesofAbenez\Orm\PetType;

require __DIR__ . "/../../bootstrap.php";

/**
 * @testCase
 */
class PetTest extends \Tester\TestCase {
  /** @var Pet */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Pet::class);
  }
  
  /**
   * @return int[]
   */
  public function getPetIds(): array {
    return [
      [1, 50,]
    ];
  }
  
  /**
   * @param int $id
   * @dataProvider getPetIds
   * @return void
   */
  public function testViewType(int $id) {
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
  public function getUserIds(): array {
    return [
      [1, 2,]
    ];
  }
  
  /**
   * @param int $user
   * @dataProvider getUserIds
   * @return void
   */
  public function testGetActivePet(int $user) {
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