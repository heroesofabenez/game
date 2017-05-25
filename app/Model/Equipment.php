<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Orm\EquipmentDummy as EquipmentEntity,
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
  /** @var \Nette\Caching\Cache */
  protected $cache;
  
  function __construct(ORM $orm, \Nette\Security\User $user, \Nette\Caching\Cache $cache) {
    $this->orm = $orm;
    $this->user = $user;
    $this->cache = $cache;
  }
  
  /**
   * Gets list of all equipment
   * 
   * @return EquipmentEntity[]
   */
  function listOfEquipment(): array {
    $equipments = $this->cache->load("equipment", function(& $dependencies) {
      $return = [];
      $equipments = $this->orm->equipment->findAll();
      /** @var \HeroesofAbenez\Orm\Equipment $equipment */
      foreach($equipments as $equipment) {
        $return[$equipment->id] = new EquipmentEntity($equipment);
      }
      return $return;
    });
    return $equipments;
  }
  
  /**
   * Gets data about specified equipment
   * @param int $id
   * @return EquipmentEntity|NULL
   */
  function view(int $id): ?EquipmentEntity {
    $equipments = $this->listOfEquipment();
    return Arrays::get($equipments, $id, NULL);
  }
  
  /**
   * Equip an item
   * 
   * @param int $id
   * @return void
   * @throws ItemNotFoundException
   * @throws ItemNotOwnedException
   * @throws ItemAlreadyEquippedException
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
   * @param int $id
   * @return void
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