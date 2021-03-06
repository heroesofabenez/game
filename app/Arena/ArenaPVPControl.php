<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

use HeroesofAbenez\Combat\Character;
use HeroesofAbenez\Model\OpponentNotFoundException;
use Nextras\Orm\Collection\ICollection;

/**
 * PVP Arena Control
 *
 * @author Jakub Konečný
 */
final class ArenaPVPControl extends ArenaControl {
  protected string $arena = "heroes";
  protected string $profileLink = "Profile:view";
  
  /**
   * @return ICollection|\HeroesofAbenez\Orm\Character[]
   */
  protected function getOpponents(): ICollection {
    $level = $this->user->identity->level;
    return $this->orm->characters->findBy([
      "currentStage" => $this->user->identity->stage,
      "level>" => $level - 5,
      "level<" => $level + 5,
      "id!=" => $this->user->id
    ])->orderBy("level");
  }
  
  /**
   * Calculate rewards from won combat
   *
   * @return int[]
   */
  protected function calculateRewards(Character $player, Character $opponent): array {
    $experience = (int) round($opponent->level / 5) + 1;
    $money = (int) round($opponent->level / 2) + 3;
    if($opponent->level > $player->level) {
      $experience += 2;
      $money += 3;
    }
    return ["money" => $money, "experience" => $experience];
  }

  /**
   * @throws OpponentNotFoundException
   */
  protected function getOpponent(int $id): Character {
    return $this->getPlayer($id);
  }
}
?>