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
   * @return \Nette\Database\Table\ActiveRow[]
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
   * Calculate rewards from won combat
   * 
   * @param Character $player
   * @param Character $opponent
   * @return array
   */
  protected function calculateRewards($player, $opponent) {
    $experience = round($opponent->level / 10) + 1;
    if($opponent->level > $player->level) $experience += 1;
    $money = round($opponent->level / 5) + 1;
    if($opponent->level > $player->level) $money += 1;
    return array("money" => $money, "experience" => $experience);
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