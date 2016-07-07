<?php
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
  function setParticipants(CharacterEntity $player, CharacterEntity $opponent) {
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
   * @param Team $opponents
   * @return CharacterEntity
   */
  protected function selectAttackTarget(CharacterEntity $attacker, Team $opponents) {
    return $opponents[0];
  }
}
?>