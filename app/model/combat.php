<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Team;

/**
 * Handles combat
 * 
 * @author Jakub Konečný
 * @property-read int $winner
 */
class CombatBase extends \Nette\Object {
  /** @var array First team */
  protected $team1 = array();
  /** @var array Second team */
  protected $team2 = array();
  /** @var int number of current round */
  protected $round;
  /** @var int */
  protected $round_limit = 30;
  
  /**
   * @param \HeroesofAbenez\Entities\Team $team1 First team
   * @param \HeroesofAbenez\Entities\Team $team2 Second team
   */
  function __construct(Team $team1, Team $team2) {
    $this->round = 0;
    $this->team1 = $team1;
    $this->team2 = $team2;
  }
  
  function getWinner() {
    static $result = 0;
    if($this->round >= $this->round_limit AND $result === 0) {
      $result = rand(1, 2);
    } elseif($this->round < $this->round_limit AND $result === 0) {
      if(!$this->team1->hasAliveMembers()) $result = 2;
      elseif(!$this->team2->hasAliveMembers()) $result = 1;
    }
    return $result;
  }
  
  /**
   * Starts next round
   * 
   * @return int Winning team/0
   */
  protected function start_round() {
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
    if($this->getWinner() > 0) return $this->getWinner();
    return 0;
  }
  
  /**
   * Ends round
   * 
   * @return int Winning team/0
   */
  protected function end_round() {
    foreach($this->team1 as &$character) {
      $character->recalculateStats();
    }
    foreach($this->team2 as &$character) {
      $character->recalculateStats();
    }
    if($this->getWinner() > 0) return $this->getWinner();
    return 0;
  }
  
  /**
   * Executes the combat
   * 
   * @return int Winning team
   */
  function execute() {
    while($this->round < $this->round_limit) {
      if($this->start_round() > 0) break;
      if($this->end_round() > 0) break;
    }
    return $this->getWinner();
  }
}
?>