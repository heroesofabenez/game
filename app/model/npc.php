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
class NPCModel {
  /**
   * Gets list of npcs
   * 
   * @param \Nette\Di\Container $container
   * @param int $stage Return npcs only from certain stage, 0 = all stages
   * @return array
   */
  static function listOfNpcs(\Nette\Di\Container $container, $stage = 0) {
    $return = array();
    $cache = $container->getService("caches.locations");
    $npcs = $cache->load("npcs");
    if($npcs === NULL) {
      $db = $container->getService("database.default.context");
      $npcs = $db->table("npcs");
      foreach($npcs as $npc) {
        $return[$npc->id] = new NPC($npc->id, $npc->name, $npc->description, $npc->race, $npc->type, $npc->sprite, $npc->portrait, $npc->stage, $npc->pos_x, $npc->pos_y);
      }
      $cache->save("npcs", $return);
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
   * @param \Nette\Di\Container $container
   * @return \HeroesofAbenez\NPC
   */
  static function view($id, \Nette\Di\Container $container) {
    $npcs = NPCModel::listOfNpcs($container);
    $npc = Arrays::get($npcs, $id, false);
    return $npc;
  }
}
