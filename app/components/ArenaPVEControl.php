<?php
namespace HeroesofAbenez\Arena;

use HeroesofAbenez\Entities\Character;

/**
 *  PVE Arena Control
 *
 * @author Jakub Konečný
 */
class ArenaPVEControl extends ArenaControl {
  /** @var string */
  protected $arena = "champions";
  
  /**
   * @return array
   */
  protected function getOpponents() {
    $level = $this->user->identity->level;
    $opponents = $this->db->table("pve_arena_opponents")
      ->where("level > $level-5")
      ->where("level < $level+5");
    return $opponents;
  }
  
  /**
   * Get data for specified npc
   * 
   * @param int $id Npc's id
   * @return Character
   * @throws OpponentNotFoundException
   */
  protected function getNpc($id) {
    $row = (array) $this->db->query("SELECT * FROM pve_arena_opponents WHERE id=$id")->fetch();
    if(count($row) === 1) throw new OpponentNotFoundException;
    $npc = new Character($row);
    return $npc;
  }
  
  /**
   * Show champion's profile
   * 
   * @return void
   */
  function renderChampion() {
    $template = $this->template;
    $template->setFile(__DIR__ . "/arenaChampion.latte");
    try {
      $template->champion = $this->getNpc($this->presenter->getParameter("id"));
    } catch(OpponentNotFoundException $e) {
      $template->champion = false;
    }
    $template->render();
  }
  
  /**
   * Fight a npc
   * 
   * @param int $npcId
   * @return void
   */
  function handleFight($npcId) {
    try {
      $npc = $this->getNpc($npcId);
    } catch(OpponentNotFoundException $e) {
      $this->presenter->forward("Npc:notfound");
    }
    $this->doDuel($npc);
  }
}

interface ArenaPVEControlFactory {
  /** @return \HeroesofAbenez\Arena\ArenaPVEControl */
  function create();
}
?>