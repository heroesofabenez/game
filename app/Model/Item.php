<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Orm\Item as ItemEntity,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\ItemDummy;

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
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Application\LinkGenerator */
  protected $linkGenerator;
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, ORM $orm, \Nette\Security\User $user) {
    $this->db = $db;
    $this->orm = $orm;
    $this->cache = $cache;
    $this->user = $user;
  }
  
  function setLinkGenerator(\Nette\Application\LinkGenerator $generator) {
    $this->linkGenerator = $generator;
  }
  
  /**
   * Gets list of all items
   * 
   * @return ItemDummy[]
   */
  function listOfItems(): array {
    $items = $this->cache->load("items", function(& $dependencies) {
      $return = [];
      $items = $this->orm->items->findAll();
      /** @var ItemEntity $item */
      foreach($items as $item) {
        $return[$item->id] = new ItemDummy($item);
      }
      return $return;
    });
    return $items;
  }
  
  /**
   * Gets name of specified item
   * 
   * @param int $id Item's id
   * @return string
   */
  function getItemName(int $id): string {
    $item = $this->view($id);
    if(is_null($item)) {
      return "";
    } else {
      return $item->name;
    }
  }
  
  /**
   * Get info about specified item
   * 
   * @param int $id Item's id
   * @return ItemDummy|NULL
   */
  function view(int $id): ?ItemDummy {
    $items = $this->listOfItems();
    return Arrays::get($items, $id, NULL);
  }
  
  /**
   * Check if player has specified item
   * 
   * @param int $id Item's id
   * @param int $amount
   * @return bool
   */
  function haveItem(int $id, int $amount = 1): bool {
    $itemRow = $this->db->table("character_items")
      ->where("character", $this->user->id)
      ->where("item", $id);
    if($itemRow->count() == 1) {
      $item = $itemRow->fetch();
      if($item->amount >= $amount) {
        return true;
      }
    }
    return false;
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
        "character" => $this->user->id, "item" => $id, "amount" => $amount,
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