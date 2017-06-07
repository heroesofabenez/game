<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\Item as ItemEntity;

require __DIR__ . "/../../bootstrap.php";

class ItemTest extends \Tester\TestCase {
  /** @var Item */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  function setUp() {
    $this->model = $this->getService(Item::class);
  }
  
  /**
   * @return void
   */
  function testView() {
    $item = $this->model->view(1);
    Assert::type(ItemEntity::class, $item);
  }
  
  /**
   * @return void
   */
  function testGetItemName() {
    $actual = $this->model->getItemName(1);
    $expected = "Book ABC";
    Assert::type("string", $actual);
  }
}

$test = new ItemTest;
$test->run();
?>