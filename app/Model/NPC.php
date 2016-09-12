<?php
namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\NPC as NPCEntity;

/**
 * Npc model
 *
 * @author Jakub Konečný
 */
class NPC {
  use \Nette\SmartObject;
  
  /** @var \Nette\Database\Context */
  protected $db;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  
  /**
   * @param \Nette\Caching\Cache $cache
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->db = $db;
    $this->cache = $cache;
  }
  
  /**
   * Gets list of npcs
   * 
   * @param int $stage Return npcs only from certain stage, 0 = all stages
   * @return NPCEntity[]
   */
  function listOfNpcs($stage = 0) {
    $return = [];
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
   * @return NPCEntity
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
    $npc = $this->view($id);
    if(!$npc) return "";
    else return $npc->name;
  }
}
?>