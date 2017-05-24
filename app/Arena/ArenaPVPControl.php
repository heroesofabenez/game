<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

use HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Model\OpponentNotFoundException,
    Nextras\Orm\Collection\ICollection;

/**
 * PVP Arena Control
 *
 * @author Jakub Konečný
 */
class ArenaPVPControl extends ArenaControl {
  /** @var string */
  protected $arena = "heroes";
  
  /**
   * @return ICollection|\HeroesofAbenez\Orm\Character
   */
  function getOpponents(): ICollection {
    $level = $this->user->identity->level;
    return $this->orm->characters->findBy([
      "currentStage" => $this->user->identity->stage,
      "level>" => $level - 5,
      "level<" => $level + 5,
      "id!=" => $this->user->id
    ]);
  }
  
  /**
   * Calculate rewards from won combat
   * 
   * @param Character $player
   * @param Character $opponent
   * @return int[]
   */
  protected function calculateRewards(Character $player, Character $opponent): array {
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
  function handleFight(int $id): void {
    try {
      $enemy = $this->getPlayer($id);
    } catch (OpponentNotFoundException $e) {
      $this->presenter->forward("Profile:notfound");
    }
    $this->doDuel($enemy);
  }
}
?>