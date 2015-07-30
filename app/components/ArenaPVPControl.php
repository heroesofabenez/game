<?php
namespace HeroesofAbenez\Arena;

use HeroesofAbenez\Entities\Team,
    HeroesofAbenez\Model\CombatBase;

/**
 * PVP Arena Control
 *
 * @author Jakub Konečný
 */
class ArenaPVPControl extends ArenaControl {
  /** @var string */
  protected $arena = "heroes";
  
  /**
   * @return array
   */
  function getOpponents() {
    $level = $this->user->identity->level;
    $opponenets = $this->db->table("characters")
      ->where("current_stage", $this->user->identity->stage)
      ->where("level > $level-5")
      ->where("level < $level+5")
      ->where("NOT id={$this->user->id}");
    return $opponenets;
  }
  
  function handleFight($id) {
    $player = $this->getPlayer($this->user->id);
    try {
      $enemy = $this->getPlayer($id);
    } catch (OpponentNotFoundException $e) {
      $this->presenter->forward("Profile:notfound");
    }
    $team1 = new Team($player->name);
    $team1->addMember($player);
    $team2 = new Team($enemy->name);
    $team2->addMember($enemy);
    $combat = new CombatBase($team1, $team2);
    $combat->execute();
    $combatId = $this->saveCombat($combat->log);
    $this->presenter->redirect("Combat:view", array("id" => $combatId));
  }
}

interface ArenaPVPControlFactory {
  /** @return \HeroesofAbenez\Arena\ArenaPVPControl */
  function create();
}
?>