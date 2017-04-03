<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\Equipment as EquipmentEntity;

/**
 * Equipment Model
 *
 * @author Jakub Konečný
 */
class Equipment {
  use \Nette\SmartObject;
  
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  
  
  /**
   * @param \Nette\Database\Context $db
   * @param \Nette\Security\User $user
   * @param \Nette\Caching\Cache $cache
   */
  function __construct(\Nette\Database\Context $db, \Nette\Security\User $user, \Nette\Caching\Cache $cache) {
    $this->db = $db;
    $this->user = $user;
    $this->cache = $cache;
  }
  
  /**
   * Gets list of all equipment
   * 
   * @return EquipmentEntity[]
   */
  function listOfEquipment(): array {
    $return = [];
    $equipments = $this->cache->load("equipment");
    if($equipments === NULL) {
      $equipments = $this->db->table("equipment");
      foreach($equipments as $eq) {
        $return[$eq->id] = new EquipmentEntity($eq);
      }
      $this->cache->save("equipment", $return);
    } else {
      $return = $equipments;
    }
    return $return;
  }
  
  /**
   * Gets data about specified equipment
   * @param int $id
   * @return EquipmentEntity|NULL
   */
  function view(int $id): ?EquipmentEntity {
    $equipments = $this->listOfEquipment();
    $item = Arrays::get($equipments, $id, NULL);
    return $item;
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
  function equipItem(int $id) {
    $item = $this->db->table("character_equipment")->get($id);
    if(!$item) {
      throw new ItemNotFoundException;
    } elseif($item->character != $this->user->id) {
      throw new ItemNotOwnedException;
    } elseif($item->worn) {
      throw new ItemAlreadyEquippedException;
    }
    $eq = $this->db->table("equipment")->get($item->item);
    $items = $this->db->table("character_equipment")
      ->where("item.slot", $eq->slot)
      ->where("character", $this->user->id);
    foreach($items as $i) {
      if($i->id == $id) continue;
      $this->db->query("UPDATE character_equipment SET ? WHERE id=?", ["worn" => 0], $i->id);
    }
    $this->db->query("UPDATE character_equipment SET ? WHERE id=?", ["worn" => 1], $id);
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
    $item = $this->db->table("character_equipment")->get($id);
    if(!$item) {
      throw new ItemNotFoundException;
    } elseif($item->character != $this->user->id) {
      throw new ItemNotOwnedException;
    } elseif(!$item->worn) {
      throw new ItemNotWornException;
    }
    $data = ["worn" => 0];
    $this->db->query("UPDATE character_equipment SET ? WHERE id=?", $data, $id);
  }
}
?>