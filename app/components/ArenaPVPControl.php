<?php
namespace HeroesofAbenez\Arena;

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