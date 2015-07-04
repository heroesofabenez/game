<?php
namespace HeroesofAbenez;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\Item;

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
  /** @var \Nette\Http\Request */
  protected $request;
  /** @var \Nette\Application\LinkGenerator */
  protected $linkGenerator;
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db, \Nette\Security\User $user) {
    $this->db = $db;
    $this->cache = $cache;
    $this->user = $user;
  }
  
  function setRequest(\Nette\Http\Request $request) {
    $this->request = $request;
  }
  
  function setLinkGenerator(\Nette\Application\LinkGenerator $generator) {
    $this->linkGenerator = $generator;
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
        $return[$item->id] = new Item($item);
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
  
  /**
   * @param int $id Items's id
   * @return int Error code|1 on success
   */
  function buyItem($id) {
    $item = $this->view($id);
    if(!$item) return 2;
    $urls = $this->canBuyFrom($id);
    if(!$this->checkReferer($urls)) return 3;
    $character = $this->db->table("characters")->get($this->user->id);
    if($character->money < $item->price) return 4;
    if(!$this->giveItem($id)) return 5;
    $data = "money=money-{$item->price}";
    $result = $this->db->query("UPDATE characters SET $data WHERE id=?", $this->user->id);
    if(!$result) return 5;
    else return 1;
  }
  
  /**
   * @param int $id
   * @return array
   */
  function canBuyFrom($id) {
    $return = array();
    $result = $this->db->table("shop_items")
       ->where("item", $id);
    foreach($result as $row) {
      $return[] = $this->linkGenerator->link("Npc:trade", array("id" => $row->npc));
    }
    return $return;
  }
  
  /**
   * Check if player came from specified url
   * 
   * @param string $urls Expected url
   * @return bool
   */
  protected function checkReferer($urls) {
    $referer = $this->request->getReferer();
    if($referer === NULL) return false;
    if(in_array($referer->absoluteUrl, $urls)) return true;
    else return false;
  }
  
}
?>