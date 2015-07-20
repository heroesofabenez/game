<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Team;

/**
 * Handles combat
 * 
 * @author Jakub Konečný
 */
class CombatBase extends \Nette\Object {
  /** @var array First team */
  protected $team1 = array();
  /** @var array Second team */
  protected $team2 = array();
  /** @var int number of current round */
  protected $round;
  
  /**
   * @param \HeroesofAbenez\Entities\TeamTeam $team1 First team
   * @param \HeroesofAbenez\Entities\TeamTeam $team2 Second team
   */
  function __construct(Team $team1, Team $team2) {
    if(!is_a($team1, "\HeroesofAbenez\Entities\TeamTeam")) { exit("Invalid value for parameter team1 passed to method CombatBase:__construct. Expected Team."); }
    if(!is_a($team2, "\HeroesofAbenez\Entities\TeamTeam")) { exit("Invalid value for parameter team2 passed to method CombatBase:__construct. Expected Team."); }
    $this->round = 0;
    $this->team1 = $team1;
    $this->team2 = $team2;
  }
  
  /**
   * Starts next round
   * 
   * @return int
   */
  function start_round() {
    $this->round++;
    foreach($this->team1 as &$character) {
      foreach($character->effects as &$effect) {
     	if(is_int($effect->duration)) { $effect->duration--; }
      }
      $character->recalculateStats();
    }
    foreach($this->team2 as &$character) {
      foreach($character->effects as &$effect) {
     	if(is_int($effect->duration)) { $effect->duration--; }
      }
      $character->recalculateStats();
    }
    if(count($this->team1->activeMembers) < 1) return 2;
    elseif(count($this->team2->activeMembers) < 1) return 1;
    return 0;
  }
  
  /**
   * @return void
   */
  function execute() { }
}
?>