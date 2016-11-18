<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

use HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Model\OpponentNotFoundException;

/**
 *  PVE Arena Control
 *
 * @author Jakub Konečný
 */
class ArenaPVEControl extends ArenaControl {
  /** @var string */
  protected $arena = "champions";
  
  /**
   * @return \Nette\Database\Table\Selection
   */
  protected function getOpponents(): \Nette\Database\Table\Selection {
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
  protected function getNpc(int $id): Character {
    try {
      $npc = $this->combatHelper->getArenaNpc($id);
    } catch(OpponentNotFoundException $e) {
      throw $e;
    }
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
      $template->champion = $this->getNpc((int) $this->presenter->getParameter("id"));
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
  protected function calculateRewards($player, $opponent): array {
    $experience = round($opponent->level / 10) + 1;
    $money = round($opponent->level / 5) + 1;
    if($opponent->level > $player->level) {
      $experience += 1;
      $money += 1;
    }
    return ["money" => $money, "experience" => $experience];
  }
  
  /**
   * Fight a npc
   * 
   * @param int $npcId
   * @return void
   */
  function handleFight(int $npcId) {
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