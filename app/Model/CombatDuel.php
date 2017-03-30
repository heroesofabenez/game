<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Team,
    HeroesofAbenez\Entities\Character as CharacterEntity;

/**
 * Combat - Duel
 *
 * @author Jakub Konečný
 */
class CombatDuel extends CombatBase {
  /**
   * @param CharacterEntity $player
   * @param CharacterEntity $opponent
   */
  function setParticipants(CharacterEntity $player, CharacterEntity $opponent): void {
    $team1 = new Team($player->name);
    $team1[] = $player;
    $team2 = new Team($opponent->name);
    $team2[] = $opponent;
    $this->setTeams($team1, $team2);
  }
  
  /**
   * Select target for attack
   * 
   * @param CharacterEntity $attacker
   * @return CharacterEntity
   */
  protected function selectAttackTarget(CharacterEntity $attacker): CharacterEntity {
    $enemyTeam = $this->getEnemyTeam($attacker);
    return $this->{"team" . $enemyTeam}[0];
  }
}
?>