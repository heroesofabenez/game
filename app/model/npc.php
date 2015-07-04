<?php
namespace HeroesofAbenez;

use Nette\Utils\Arrays;

/**
 * Npc model
 *
 * @author Jakub Konečný
 */
class NPCModel extends \Nette\Object {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \HeroesofAbenez\ItemModel */
  protected $itemModel;
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->db = $db;
    $this->cache = $cache;
  }
  
  function setItemModel(\HeroesofAbenez\ItemModel $model) {
    $this->itemModel = $model;
  }
  
  /**
   * Gets list of npcs
   * 
   * @param int $stage Return npcs only from certain stage, 0 = all stages
   * @return array
   */
  function listOfNpcs($stage = 0) {
    $return = array();
    $npcs = $this->cache->load("npcs");
    if($npcs === NULL) {
      $npcs = $this->db->table("npcs");
      foreach($npcs as $npc) {
        $return[$npc->id] = new NPC($npc);
      }
      $this->cache->save("npcs", $return);
    } else {
      $return = $npcs;
    }
    if($stage > 0) {
      foreach($return as $npc) {
        if($npc->stage !== $stage) unset($return[$npc->id]);
      }
    }
    return $return;
  }
  
  /**
   * Get info about specified npc
   * 
   * @param int $id Npc's id
   * @return \HeroesofAbenez\NPC
   */
  function view($id) {
    $npcs = $this->listOfNpcs();
    $npc = Arrays::get($npcs, $id, false);
    return $npc;
  }
  
  /**
   * Get name of specified npc
   * 
   * @param int $id Npc's id
   * @return string
   */
  function getNpcName($id) {
    $npcs = $this->listOfNpcs();
    return $npcs[$id]->name;
  }
  
  /**
   * 
   * @param int $id Npc's id
   * @return array Items to buy
   */
  function shop($id) {
    $return = array();
    $items = $this->db->table("shop_items")
      ->where("npc", $id)
      ->order("order");
    foreach($items as $item) {
      $return[] = $this->itemModel->view($item->item);
    }
    return $return;
  }
}
?>