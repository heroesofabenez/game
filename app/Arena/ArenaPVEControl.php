<?php
declare(strict_types=1);

namespace HeroesofAbenez\Arena;

use HeroesofAbenez\Combat\Character;
use HeroesofAbenez\Model\OpponentNotFoundException;
use Nextras\Orm\Collection\ICollection;
use HeroesofAbenez\Orm\PveArenaOpponent;

/**
 *  PVE Arena Control
 *
 * @author Jakub Konečný
 */
final class ArenaPVEControl extends ArenaControl {
  protected string $arena = "champions";
  protected string $profileLink = "Arena:champion";
  
  /**
   * @return ICollection|PveArenaOpponent[]
   */
  protected function getOpponents(): ICollection {
    $level = $this->user->identity->level;
    return $this->orm->arenaNpcs->findBy([
      "level>" => $level - 5,
      "level<" => $level + 5
    ])->orderBy("level");
  }
  
  /**
   * Get data for specified npc
   *
   * @throws OpponentNotFoundException
   */
  private function getNpc(int $id): Character {
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
    $this->template->setFile(__DIR__ . "/arenaChampion.latte");
    try {
      $this->template->champion = $this->getNpc($id);
      $this->template->champion->applyEffectProviders();
      /** @var PveArenaOpponent $npc */
      $npc = $this->orm->arenaNpcs->getById($id);
      $this->template->race = $npc->race->name;
      $this->template->occupation = $npc->class->name;
      $this->template->specialization = ($npc->specialization !== null) ? $npc->specialization->name : null;
    } catch(OpponentNotFoundException) {
      $this->template->champion = false;
    }
    $this->template->render();
  }
  
  /**
   * Calculate rewards from won combat
   *
   * @return int[]
   */
  protected function calculateRewards(Character $player, Character $opponent): array {
    $experience = (int) round($opponent->level / 10) + 1;
    $money = (int) round($opponent->level / 5) + 1;
    if($opponent->level > $player->level) {
      $experience++;
      $money++;
    }
    return ["money" => $money, "experience" => $experience];
  }

  /**
   * @throws OpponentNotFoundException
   */
  protected function getOpponent(int $id): Character {
    return $this->getNpc($id);
  }
}
?>