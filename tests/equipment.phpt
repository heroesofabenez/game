<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT;
use MyTester\Assert;

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
    Assert::type("HeroesofAbenez\Entities\Equipment", $items[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView($id) {
    $item = $this->model->view($id);
    Assert::type("HeroesofAbenez\Entities\Equipment", $item);
  }
}

/*$suit = new EquipmentTest($container->getService("hoa.model.equipment"));
$suit->run();*/
?>
