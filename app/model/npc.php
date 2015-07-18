<?php
namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\NPC as NPCEntity;

/**
 * Npc model
 *
 * @author Jakub Konečný
 */
class NPC extends \Nette\Object {
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \HeroesofAbenez\Model\Item */
  protected $itemModel;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->db = $db;
    $this->cache = $cache;
  }
  
  function setItemModel(\HeroesofAbenez\Model\Item $model) {
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
        $return[$npc->id] = new NPCEntity($npc);
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
   * @return \HeroesofAbenez\Entities\NPC
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
}
?>