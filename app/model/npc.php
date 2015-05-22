<?php
namespace HeroesofAbenez;

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
        $return[$npc->id] = new NPC($npc->id, $npc->name, $npc->race, $npc->sprite, $npc->portrait, $npc->stage, $npc->pos_x, $npc->pos_y);
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
  
}
