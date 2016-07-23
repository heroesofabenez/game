<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\Equipment;

class EquipmentTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\Equipment */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Equipment $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testListOfEquipment() {
    $items = $this->model->listOfEquipment();
    Assert::type("array", $items);
    Assert::type(Equipment::class, $items[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView($id) {
    $item = $this->model->view($id);
    Assert::type(Equipment::class, $item);
  }
}

/*$suit = new EquipmentTest($container->getService("hoa.model.equipment"));
$suit->run();*/
?>
