<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\Equipment as EquipmentEntity;

class EquipmentTest extends MT\TestCase {
  /** @var Equipment */
  protected $model;
  
  function __construct(Equipment $model) {
    $this->model = $model;
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