<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\Item as ItemEntity;

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

  public function testHaveItemGiveItemLoseItem() {
    Assert::false($this->model->haveItem(5000));
    Assert::false($this->model->haveItem(1));
    $this->model->loseItem(1);
    Assert::false($this->model->haveItem(1));
    $this->model->giveItem(1);
    Assert::true($this->model->haveItem(1, 1));
    $this->model->giveItem(1);
    Assert::true($this->model->haveItem(1, 2));
    $this->model->loseItem(1);
    Assert::true($this->model->haveItem(1, 1));
    $this->model->loseItem(1);
    Assert::false($this->model->haveItem(1));
  }
}

$test = new ItemTest();
$test->run();
?>