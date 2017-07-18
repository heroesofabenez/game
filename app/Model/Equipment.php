<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Equipment as EquipmentEntity,
    HeroesofAbenez\Orm\Model as ORM;

/**
 * Equipment Model
 *
 * @author Jakub Konečný
 */
class Equipment {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  
  function __construct(ORM $orm, \Nette\Security\User $user) {
    $this->orm = $orm;
    $this->user = $user;
  }
  
  /**
   * Gets data about specified equipment
   */
  function view(int $id): ?EquipmentEntity {
    return $this->orm->equipment->getById($id);
  }
  
  /**
   * Equip an item
   */
  function equipItem(int $id): void {
    $item = $this->orm->characterEquipment->getById($id);
    if(is_null($item)) {
      throw new ItemNotFoundException;
    } elseif($item->character->id !== $this->user->id) {
      throw new ItemNotOwnedException;
    } elseif($item->worn) {
      throw new ItemAlreadyEquippedException;
    }
    $items = $this->orm->characterEquipment->findByCharacterAndSlot($this->user->id, $item->item->slot);
    foreach($items as $item) {
      $item->worn = ($item->id === $id);
      $this->orm->characterEquipment->persist($item);
    }
    $this->orm->characterEquipment->flush();
  }
  
  /**
   * Unequip an item
   *
   * @throws ItemNotFoundException
   * @throws ItemNotOwnedException
   * @throws ItemNotWornException
   */
  function unequipItem(int $id): void {
    $item = $this->orm->characterEquipment->getById($id);
    if(is_null($item)) {
      throw new ItemNotFoundException;
    } elseif($item->character->id !== $this->user->id) {
      throw new ItemNotOwnedException;
    } elseif(!$item->worn) {
      throw new ItemNotWornException;
    }
    $item->worn = false;
    $this->orm->characterEquipment->persistAndFlush($item);
  }
}
?>