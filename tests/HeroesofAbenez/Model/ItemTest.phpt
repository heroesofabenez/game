<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\ItemDummy;

class ItemTest extends MT\TestCase {
  /** @var Item */
  protected $model;
  
  function __construct(Item $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function testListOfItems() {
    $items = $this->model->listOfItems();
    Assert::type("array", $items);
    Assert::type(ItemDummy::class, $items[1]);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView(int $id) {
    $item = $this->model->view($id);
    Assert::type(ItemDummy::class, $item);
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testGetItemName(int $id) {
    $actual = $this->model->getItemName($id);
    $expected = "Book ABC";
    Assert::type("string", $actual);
  }
}
?>