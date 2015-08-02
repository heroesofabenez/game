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
    $team1->addMember($player);
    $team2 = new Team($opponent->name);
    $team2->addMember($opponent);
    $this->setTeams($team1, $team2);
  }
}
?>