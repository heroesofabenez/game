<?php
namespace HeroesofAbenez;

use Nette\Utils\Arrays;

/**
 * Data structure for item
 * 
 * @author Jakub Konečný
 */
class Item extends \Nette\Object {
  /** @var int */
  public $id;
  /** @var string */
  public $name;
  /** @var string */
  public $description;
  /** @var string */
  public $image;
  /** @var int */
  public $price;
  
  /**
   * @param int $id
   * @param string $name
   * @param string $description
   * @param string $image
   * @param int $price
   */
  function __construct($id, $name, $description, $image, $price = 0) {
    $this->id = (int) $id;
    $this->name = $name;
    $this->description = $description;
    $this->image = $image;
    $this->price = (int) $price;
  }
}

/**
 * Item Model
 *
 * @author Jakub Konečný
 */
class ItemModel extends \Nette\Object {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \Nette\Security\User */
  protected $user;
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, \Nette\Security\User $user) {
    $this->db = $db;
    $this->cache = $cache;
    $this->user = $user;
  }
  
  /**
   * Gets list of all items
   * 
   * @return array
   */
  function listOfItems() {
    $return = array();
    $items = $this->cache->load("items");
    if($items === NULL) {
      $items = $this->db->table("items");
      foreach($items as $item) {
        $return[$item->id] = new Item($item->id, $item->name, $item->description, $item->image, $item->price);
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
   * @return \HeroesofAbenez\Item
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
    if($itemRow->count("id") == 1) {
      foreach($itemRow as $item) { }
      if($item->amount >= $amount) $return = true;
    }
    return $return;
  }
  
  /**
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