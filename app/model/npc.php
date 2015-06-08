<?php
namespace HeroesofAbenez;

use Nette\Utils\Arrays;

/**
 * Data structure for npc
 * 
 * @author Jakub Konečný
 */
class NPC extends \Nette\Object {
  /** @var int id */
  public $id;
  /** @var string name */
  public $name;
  /** @var string descrption */
  public $description;
  /** @var int id of race */
  public $race;
  /** @var string type of npc */
  public $type;
  /** @var string */
  public $sprite;
  /** @var string */
  public $portrait;
  /** @var int id of stage */
  public $stage;
  /** @var int */
  public $pos_x;
  /** @var int */
  public $pos_y;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->name != "npcs") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}

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
  
  function __construct(\Nette\Caching\Cache $cache, \Nette\Database\Context $db) {
    $this->db = $db;
    $this->cache = $cache;
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
}
?>