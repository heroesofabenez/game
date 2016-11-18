<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

use HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Model\OpponentNotFoundException;

/**
 * PVP Arena Control
 *
 * @author Jakub Konečný
 */
class ArenaPVPControl extends ArenaControl {
  /** @var string */
  protected $arena = "heroes";
  
  /**
   * @return \Nette\Database\Table\Selection
   */
  function getOpponents(): \Nette\Database\Table\Selection {
    $level = $this->user->identity->level;
    $opponents = $this->db->table("characters")
      ->where("current_stage", $this->user->identity->stage)
      ->where("level > $level-5")
      ->where("level < $level+5")
      ->where("NOT id={$this->user->id}");
    return $opponents;
  }
  
  /**
   * Calculate rewards from won combat
   * 
   * @param Character $player
   * @param Character $opponent
   * @return array
   */
  protected function calculateRewards($player, $opponent): array {
    $experience = round($opponent->level / 5) + 1;
    $money = round($opponent->level / 2) + 3;
    if($opponent->level > $player->level) {
      $experience += 2;
      $money += 3;
    }
    return ["money" => $money, "experience" => $experience];
  }
  
  /**
   * Fight a player
   * 
   * @param int $id Player's id
   * @return void
   */
  function handleFight(int $id) {
    try {
      $enemy = $this->getPlayer($id);
    } catch (OpponentNotFoundException $e) {
      $this->presenter->forward("Profile:notfound");
    }
    $this->doDuel($enemy);
  }
}

interface ArenaPVPControlFactory {
  /** @return \HeroesofAbenez\Arena\ArenaPVPControl */
  function create();
}
?>