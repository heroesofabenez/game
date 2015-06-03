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
  
  /**
   * @param int $id
   * @param string $name
   * @param string $description
   * @param int $race
   * @param string $type
   * @param string $sprite
   * @param string $portrait
   * @param int $stage
   * @param int $pos_x
   * @param int $pos_y
   */
  function __construct($id, $name, $description, $race, $type, $sprite, $portrait, $stage, $pos_x, $pos_y) {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->race = $race;
    $this->type = $type;
    $this->sprite = $sprite;
    $this->portrait = $portrait;
    $this->stage = $stage;
    $this->pos_x = $pos_x;
    $this->pos_y = $pos_y;
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
        $return[$npc->id] = new NPC($npc->id, $npc->name, $npc->description, $npc->race, $npc->type, $npc->sprite, $npc->portrait, $npc->stage, $npc->pos_x, $npc->pos_y);
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
