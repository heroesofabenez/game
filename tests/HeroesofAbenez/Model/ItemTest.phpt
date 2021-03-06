<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Tester\Assert;
use HeroesofAbenez\Orm\Item as ItemEntity;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 * @testCase
 */
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
    \Tester\Environment::lock("database", __DIR__ . "/../../..");
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

  public function testCanEquipItem() {
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    /** @var ItemEntity $item */
    $item = $this->model->view(6);
    $oldLevel = $item->requiredLevel;
    $oldClass = $item->requiredClass;
    $oldSpecialization =  $item->requiredSpecialization;
    Assert::true($this->model->canEquipItem($item));
    $item->requiredLevel = 999;
    Assert::false($this->model->canEquipItem($item));
    $item->requiredLevel = $oldLevel;
    $item->requiredClass = 1;
    Assert::false($this->model->canEquipItem($item));
    $item->requiredClass = $oldClass;
    $item->requiredSpecialization = 1;
    Assert::false($this->model->canEquipItem($item));
    $item->requiredSpecialization = $oldSpecialization;
    $orm->items->persistAndFlush($item);
  }
}

$test = new ItemTest();
$test->run();
?>