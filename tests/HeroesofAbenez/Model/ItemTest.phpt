<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert,
    HeroesofAbenez\Orm\Item as ItemEntity;

require __DIR__ . "/../../bootstrap.php";

final class ItemTest extends \Tester\TestCase {
  /** @var Item */
  protected $model;
  
  use \Testbench\TCompiledContainer;
  
  public function setUp() {
    $this->model = $this->getService(Item::class);
  }
  
  public function testView() {
    $item = $this->model->view(1);
    Assert::type(ItemEntity::class, $item);
    Assert::null($this->model->view(5000));
  }
  
  public function testGetItemName() {
    Assert::notSame("", $this->model->getItemName(1));
    Assert::same("", $this->model->getItemName(5000));
  }
}

$test = new ItemTest();
$test->run();
?>