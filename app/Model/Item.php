<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\Item as ItemEntity;

/**
 * Item Model
 *
 * @author Jakub Konečný
 * @property-write \Nette\Application\LinkGenerator $linkGenerator
 */
class Item {
  use \Nette\SmartObject;
  
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Application\LinkGenerator */
  protected $linkGenerator;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   * @param \Nette\Security\User $user
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, \Nette\Security\User $user) {
    $this->db = $db;
    $this->cache = $cache;
    $this->user = $user;
  }
  
  function setLinkGenerator(\Nette\Application\LinkGenerator $generator) {
    $this->linkGenerator = $generator;
  }
  
  /**
   * Gets list of all items
   * 
   * @return ItemEntity[]
   */
  function listOfItems(): array {
    $return = [];
    $items = $this->cache->load("items");
    if($items === NULL) {
      $items = $this->db->table("items");
      foreach($items as $item) {
        $return[$item->id] = new ItemEntity($item);
      }
      $this->cache->save("items", $return);
    } else {
      $return = $items;
    }
    return $return;
  }
  
  /**
   * Gets name of specified item
   * 
   * @param int $id Item's id
   * @return string
   */
  function getItemName(int $id): string {
    $item = $this->view($id);
    if(!$item) {
      return "";
    } else {
      return $item->name;
    }
  }
  
  /**
   * Get info about specified item
   * 
   * @param int $id Item's id
   * @return ItemEntity
   */
  function view(int $id): ItemEntity {
    $items = $this->listOfItems();
    $item = Arrays::get($items, $id, false);
    return $item;
  }
  
  /**
   * Check if player has specified item
   * 
   * @param int $id Item's id
   * @param int $amount
   * @return bool
   */
  function haveItem(int $id, int $amount = 1): bool {
    $return = false;
    $itemRow = $this->db->table("character_items")
      ->where("character", $this->user->id)
      ->where("item", $id);
    if($itemRow->count() == 1) {
      $item = $itemRow->fetch();
      if($item->amount >= $amount) {
        $return = true;
      }
    }
    return $return;
  }
  
  /**
   * Give the player item(s)
   * 
   * @param int $id Item's id
   * @param int $amount
   * @return \Nette\Database\ResultSet
   */
  function giveItem(int $id, int $amount = 1): \Nette\Database\ResultSet {
    if($this->haveItem($id)) {
      $data = "item=$id, amount=amount+$amount";
      $where = ["character" => $this->user->id, "item" => $id];
      $result = $this->db->query("UPDATE character_items SET $data WHERE ?", $where);
      return $result;
    } else {
      $data = [
        "character" => $this->user->id, "item" => $id, "amount" => $amount
      ];
      $result = $this->db->query("INSERT INTO character_items", $data);
      return $result;
    }
  }
  
  /**
   * 
   * @param int $id Item's id
   * @param int $amount
   * @return bool
   */
  function loseItem(int $id, int $amount = 1): bool {
    $data = "amount=amount-$amount";
    $wheres = ["character" => $this->user->id, "item" => $id];
    $result = $this->db->query("UPDATE character_items SET $data WHERE ?", $wheres);
    if(!$result) {
      return false;
    } else {
      return true;
    }
  }
}
?>