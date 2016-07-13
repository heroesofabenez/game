<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT;
use MyTester\Assert;

class ItemTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\Item */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Item $model) {
    $this->model = $model;
  }
  
  function testListOfItems() {
    $items = $this->model->listOfItems();
    Assert::type("array", $items);
    Assert::type("HeroesofAbenez\Entities\Item", $items[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView($id) {
    $item = $this->model->view($id);
    Assert::type("HeroesofAbenez\Entities\Item", $item);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetItemName($id) {
    $actual = $this->model->getItemName($id);
    $expected = "Book ABC";
    Assert::type("string", $actual);
    //Assert::same($expected, $actual);
  }
}

/*$suit = new ItemTest($container->getService("hoa.model.item"));
$suit->run();*/
?>