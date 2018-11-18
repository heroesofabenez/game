<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Item as ItemEntity;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\CharacterItem;

/**
 * Item Model
 *
 * @author Jakub Konečný
 */
final class Item {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  
  public function __construct(ORM $orm, \Nette\Security\User $user) {
    $this->orm = $orm;
    $this->user = $user;
  }
  
  /**
   * Get info about specified item
   */
  public function view(int $id): ?ItemEntity {
    return $this->orm->items->getById($id);
  }
  
  /**
   * Check if player has specified item
   */
  public function haveItem(int $id, int $amount = 1): bool {
    $item = $this->orm->characterItems->getByCharacterAndItem($this->user->id, $id);
    if(is_null($item)) {
      return false;
    }
    return ($item->amount >= $amount);
  }
  
  /**
   * Give the player item(s)
   */
  public function giveItem(int $id, int $amount = 1): void {
    $item = $this->orm->characterItems->getByCharacterAndItem($this->user->id, $id);
    if(is_null($item)) {
      $item = new CharacterItem();
      $this->orm->characterItems->attach($item);
      $item->character = $this->user->id;
      $item->item = $id;
      $item->amount = 0;
    }
    $item->amount += $amount;
    $this->orm->characterItems->persistAndFlush($item);
  }
  
  public function loseItem(int $id, int $amount = 1): void {
    $item = $this->orm->characterItems->getByCharacterAndItem($this->user->id, $id);
    if(is_null($item)) {
      return;
    }
    $item->amount -= $amount;
    if($item->amount < 1) {
      $this->orm->characterItems->removeAndFlush($item);
    } else {
      $this->orm->characterItems->persistAndFlush($item);
    }
  }

  public function canEquipItem(ItemEntity $item): bool {
    if($this->user->identity->level < $item->requiredLevel) {
      return false;
    } elseif(!is_null($item->requiredClass) AND $item->requiredClass->id !== $this->user->identity->occupation) {
      return false;
    }
    return true;
  }

  /**
   * Equip an item
   *
   * @throws ItemNotFoundException
   * @throws ItemNotOwnedException
   * @throws ItemNotEquipableException
   * @throws ItemAlreadyEquippedException
   */
  public function equipItem(int $id): void {
    $item = $this->orm->characterItems->getById($id);
    if(is_null($item)) {
      throw new ItemNotFoundException();
    } elseif($item->character->id !== $this->user->id) {
      throw new ItemNotOwnedException();
    } elseif(!in_array($item->item->slot, ItemEntity::getEquipmentTypes(), true) OR !$this->canEquipItem($item->item)) {
      throw new ItemNotEquipableException();
    } elseif($item->worn) {
      throw new ItemAlreadyEquippedException();
    }
    $items = $this->orm->characterItems->findByCharacterAndSlot($this->user->id, $item->item->slot);
    foreach($items as $item) {
      $item->worn = ($item->id === $id);
      $this->orm->characterItems->persist($item);
    }
    $this->orm->characterItems->flush();
  }

  /**
   * Unequip an item
   *
   * @throws ItemNotFoundException
   * @throws ItemNotOwnedException
   * @throws ItemNotWornException
   */
  public function unequipItem(int $id): void {
    $item = $this->orm->characterItems->getById($id);
    if(is_null($item)) {
      throw new ItemNotFoundException();
    } elseif($item->character->id !== $this->user->id) {
      throw new ItemNotOwnedException();
    } elseif(!$item->worn) {
      throw new ItemNotWornException();
    }
    $item->worn = false;
    $this->orm->characterItems->persistAndFlush($item);
  }
}
?>