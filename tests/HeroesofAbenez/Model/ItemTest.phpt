<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use MyTester as MT,
    MyTester\Assert,
    HeroesofAbenez\Orm\Item as ItemEntity;

class ItemTest extends MT\TestCase {
  /** @var Item */
  protected $model;
  
  function __construct(Item $model) {
    $this->model = $model;
  }
  
  /**
   * @param int $id
   * @data(1)
   * @return void
   */
  function testView(int $id) {
    $item = $this->model->view($id);
    Assert::type(ItemEntity::class, $item);
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