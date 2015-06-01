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
  
  function __construct($id, $name, $description, $image) {
    $this->id = (int) $id;
    $this->name = $name;
    $this->description = $description;
    $this->image = $image;
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
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->db = $db;
    $this->cache = $cache;
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
        $return[$item->id] = new Item($item->id, $item->name, $item->description, $item->image);
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
}
?>