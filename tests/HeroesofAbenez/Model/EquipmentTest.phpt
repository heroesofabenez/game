<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\EquipmentDummy as EquipmentEntity;

class EquipmentTest extends MT\TestCase {
  /** @var Equipment */
  protected $model;
  
  function __construct(Equipment $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testListOfEquipment() {
    $items = $this->model->listOfEquipment();
    Assert::type("array", $items);
    Assert::type(EquipmentEntity::class, $items[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView(int $id) {
    $item = $this->model->view($id);
    Assert::type(EquipmentEntity::class, $item);
  }
}
?>