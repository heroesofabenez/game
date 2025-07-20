<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterItem;
use Tester\Assert;
use HeroesofAbenez\Orm\Item as ItemEntity;

require __DIR__ . "/../../bootstrap.php";

/**
 * @author Jakub Konečný
 */
final class ItemTest extends \Tester\TestCase {
  use TCharacterControl;

  private Item $model;
  
  public function setUp(): void {
    $this->model = $this->getService(Item::class); // @phpstan-ignore assign.propertyType
  }
  
  public function testView(): void {
    $item = $this->model->view(1);
    Assert::type(ItemEntity::class, $item);
    Assert::null($this->model->view(5000));
  }

  public function testHaveItemGiveItemLoseItem(): void {
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
    $this->preserveStats(["money", ], function() {
      $character = $this->getCharacter();
      $oldMoney = $character->money;
      $this->model->giveItem(12, 2, true);
      Assert::true($this->model->haveItem(12, 2));
      $newMoney = $character->money;
      Assert::same($oldMoney - 8, $newMoney);
      $this->model->loseItem(1, 2);
    });
  }

  public function testCanEquipItem(): void {
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

  public function testEquipItemUnequipItem(): void {
    \Tester\Environment::lock("database", __DIR__ . "/../../..");
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    Assert::exception(function() {
      $this->model->equipItem(5000);
    }, ItemNotFoundException::class);
    Assert::exception(function() {
      $this->model->unequipItem(5000);
    }, ItemNotFoundException::class);
    $characterItem = new CharacterItem();
    $characterItem->character = $orm->characters->getById(2); // @phpstan-ignore assign.propertyType
    $characterItem->item = $orm->items->getById(1); // @phpstan-ignore assign.propertyType
    $orm->persistAndFlush($characterItem);
    Assert::exception(function() use ($characterItem) {
      $this->model->equipItem($characterItem->id);
    }, ItemNotOwnedException::class);
    Assert::exception(function() use ($characterItem) {
      $this->model->unequipItem($characterItem->id);
    }, ItemNotOwnedException::class);
    $characterItem->character = $this->getCharacter();
    $orm->persistAndFlush($characterItem);
    Assert::exception(function() use ($characterItem) {
      $this->model->equipItem($characterItem->id);
    }, ItemNotEquipableException::class);
    $orm->removeAndFlush($characterItem);
    /** @var CharacterItem $characterItem */
    $characterItem = $orm->characterItems->getById(1);
    Assert::exception(function() use ($characterItem) {
      $this->model->equipItem($characterItem->id);
    }, ItemAlreadyEquippedException::class);
    $this->model->unequipItem($characterItem->id);
    Assert::false($characterItem->worn);
    Assert::exception(function() use ($characterItem) {
      $this->model->unequipItem($characterItem->id);
    }, ItemNotWornException::class);
    $this->model->equipItem($characterItem->id);
    Assert::true($characterItem->worn);
  }

  public function testRepairItem(): void {
    \Tester\Environment::lock("database", __DIR__ . "/../../..");
    /** @var \HeroesofAbenez\Orm\Model $orm */
    $orm = $this->getService(\HeroesofAbenez\Orm\Model::class);
    Assert::exception(function() {
      $this->model->repairItem(5000);
    }, ItemNotFoundException::class);
    $characterItem = new CharacterItem();
    $characterItem->character = $orm->characters->getById(2); // @phpstan-ignore assign.propertyType
    $characterItem->item = $orm->items->getById(10); // @phpstan-ignore assign.propertyType
    $orm->persistAndFlush($characterItem);
    Assert::exception(function() use ($characterItem) {
      $this->model->repairItem($characterItem->id);
    }, ItemNotOwnedException::class);
    $characterItem->character = $this->getCharacter();
    $orm->persistAndFlush($characterItem);
    Assert::exception(function() use ($characterItem) {
      $this->model->repairItem($characterItem->id);
    }, ItemNotDamagedException::class);
    $characterItem->durability = 0;
    $orm->persistAndFlush($characterItem);
    $this->modifyCharacter(["money" => 1, ], function() use ($characterItem) {
      Assert::exception(function() use ($characterItem) {
        $this->model->repairItem($characterItem->id);
      }, InsufficientFundsException::class);
    });
    $this->preserveStats(["money", ], function() use ($characterItem) {
      $oldMoney = $this->getCharacterStat("money");
      $this->model->repairItem($characterItem->id);
      Assert::same($characterItem->durability, $characterItem->maxDurability);
      Assert::notSame($oldMoney, $this->getCharacterStat("money"));
    });
    $orm->removeAndFlush($characterItem);
  }
}

$test = new ItemTest();
$test->run();
?>