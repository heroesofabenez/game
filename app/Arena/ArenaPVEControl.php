<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

use HeroesofAbenez\Combat\Character,
    HeroesofAbenez\Model\OpponentNotFoundException,
    Nextras\Orm\Collection\ICollection,
    HeroesofAbenez\Orm\PveArenaOpponent;

/**
 *  PVE Arena Control
 *
 * @author Jakub Konečný
 */
class ArenaPVEControl extends ArenaControl {
  /** @var string */
  protected $arena = "champions";
  
  /**
   * @return ICollection|PveArenaOpponent[]
   */
  protected function getOpponents(): ICollection {
    $level = $this->user->identity->level;
    return $this->orm->arenaNpcs->findBy([
      "level>" => $level - 5,
      "level<" => $level + 5
    ]);
  }
  
  /**
   * Get data for specified npc
   *
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
   */
  public function renderChampion(int $id): void {
    $template = $this->template;
    $template->setFile(__DIR__ . "/arenaChampion.latte");
    try {
      $template->champion = $this->getNpc($id);
    } catch(OpponentNotFoundException $e) {
      $template->champion = false;
    }
    $template->render();
  }
  
  /**
   * Calculate rewards from won combat
   *
   * @return int[]
   */
  protected function calculateRewards(Character $player, Character $opponent): array {
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
   */
  public function handleFight(int $npcId): void {
    try {
      $npc = $this->getNpc($npcId);
    } catch(OpponentNotFoundException $e) {
      $this->presenter->forward("Npc:notfound");
    }
    $this->doDuel($npc);
  }
}
?>