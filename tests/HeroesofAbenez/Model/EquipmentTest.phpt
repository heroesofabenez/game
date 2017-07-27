<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\Equipment as EquipmentEntity;

require __DIR__ . "/../../bootstrap.php";

class EquipmentTest extends \Tester\TestCase {
  /** @var Equipment */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Equipment::class);
  }
  
  /**
   * @return void
   */
  public function testView() {
    $item = $this->model->view(1);
    Assert::type(EquipmentEntity::class, $item);
  }
}

$test = new EquipmentTest;
$test->run();
?>