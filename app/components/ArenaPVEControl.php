<?php
namespace HeroesofAbenez\Arena;

use HeroesofAbenez\Entities\Team,
    HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Model\CombatBase;

/**
 *  PVE Arena Control
 *
 * @author Jakub Konečný
 */
class ArenaPVEControl extends ArenaControl {
  /** @var string */
  protected $file = "arenaPVE";
  
  /**
   * @return array
   */
  function getOpponents() {
    $level = $this->user->identity->level;
    $opponents = $this->db->table("pve_arena_opponents")
      ->where("level > $level-5")
      ->where("level < $level+5");
    return $opponents;
  }
  
  /**
   * Get data for specified npc
   * 
   * @param int $id Npc's id
   * @return Character
   * @throws OpponentNotFoundException
   */
  protected function getNpc($id) {
    $row = (array) $this->db->query("SELECT * FROM pve_arena_opponents WHERE id=$id")->fetch();
    if(count($row) === 1) throw new OpponentNotFoundException;
    $npc = new Character($row);
    return $npc;
  }
  
  /**
   * Fight a npc
   * 
   * @param int $npcId
   * @return void
   */
  function handleFight($npcId) {
    $player = $this->getPlayer($this->user->id);
    try {
      $npc = $this->getNpc($npcId);
    } catch(OpponentNotFoundException $e) {
      $this->presenter->forward("Npc:notfound");
    }
    $team1 = new Team($player->name);
    $team1->addMember($player);
    $team2 = new Team($npc->name);
    $team2->addMember($npc);
    $combat = new CombatBase($team1, $team2);
    $combat->execute();
    $log = "";
    foreach($combat->log as $text) {
      $log .= "$text<br>\n";
    }
    $combatId = $this->log->write($log);
    $this->presenter->redirect("Combat:view", array("id" => $combatId));
  }
}

interface ArenaPVEControlFactory {
  /** @return \HeroesofAbenez\Arena\ArenaPVEControl */
  function create();
}
?>