<?php
namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\Item as ItemEntity;

/**
 * Item Model
 *
 * @author Jakub Konečný
 */
class Item extends \Nette\Object {
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
  function listOfItems() {
    $return = array();
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
  function getItemName($id) {
    $items = $this->listOfItems();
    $item = Arrays::get($items, $id, false);
    if(!$item) return "";
    else return $item->name;
  }
  
  /**
   * Get info about specified item
   * 
   * @param int $id Item's id
   * @return \HeroesofAbenez\Entities\Item
   */
  function view($id) {
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
  function haveItem($id, $amount = 1) {
    $return = false;
    $itemRow = $this->db->table("character_items")
      ->where("character", $this->user->id)
      ->where("item", $id);
    if($itemRow->count() == 1) {
      $item = $itemRow->fetch();
      if($item->amount >= $amount) $return = true;
    }
    return $return;
  }
  
  /**
   * Give the player item(s)
   * 
   * @param int $id Item's id
   * @param int $amount
   * @return bool
   */
  function giveItem($id, $amount = 1) {
    if($this->haveItem($id)) {
      $data = "item=$id, amount=amount+$amount";
      $where = array("character" => $this->user->id, "item" => $id);
      $result = $this->db->query("UPDATE character_items SET $data WHERE ?", $where);
      return $result;
    } else {
      $data = array(
        "character" => $this->user->id, "item" => $id, "amount" => $amount
      );
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
  function loseItem($id, $amount = 1) {
    $data = "amount=amount-$amount";
    $wheres = array("character" => $this->user->id, "item" => $id);
    $result = $this->db->query("UPDATE character_items SET $data WHERE ?", $wheres);
    if(!$result) return false;
    else return true;
  }
}
?>