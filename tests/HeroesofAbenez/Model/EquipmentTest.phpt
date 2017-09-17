<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\Equipment as EquipmentEntity;

require __DIR__ . "/../../bootstrap.php";

final class EquipmentTest extends \Tester\TestCase {
  /** @var Equipment */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Equipment::class);
  }
  
  public function testView() {
    Assert::type(EquipmentEntity::class, $this->model->view(1));
    Assert::null($this->model->view(5000));
  }
}

$test = new EquipmentTest;
$test->run();
?>