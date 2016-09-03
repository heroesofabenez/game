<?php
namespace HeroesofAbenez\Tests;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Entities\Item;

class ItemTest extends MT\TestCase {
  /** @var \HeroesofAbenez\Model\Item */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Item $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testListOfItems() {
    $items = $this->model->listOfItems();
    Assert::type("array", $items);
    Assert::type(Item::class, $items[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView($id) {
    $item = $this->model->view($id);
    Assert::type(Item::class, $item);
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
  }
}
?>