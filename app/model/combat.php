<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Team,
    HeroesofAbenez\Entities\Character as CharacterEntity,
    HeroesofAbenez\Entities\CharacterEffect,
    HeroesofAbenez\Entities\CombatAction;

/**
 * Handles combat
 * 
 * @author Jakub Konečný
 * @property-read int $winner
 * @method void onStart() Tasks to do at the start of the combat
 * @method void onEnd() Tasks to do at the end of the combat
 */
class CombatBase extends \Nette\Object {
  /** @var \HeroesofAbenez\Entities\Team First team */
  protected $team1 = array();
  /** @var \HeroesofAbenez\Entities\Team Second team */
  protected $team2 = array();
  /** @var \HeroesofAbenez\Model\CombatLog */
  protected $log;
  /** @var int number of current round */
  protected $round;
  /** @var int */
  protected $round_limit = 30;
  /** @var array Tasks to do at the start of the combat */
  public $onStart = array();
  /** @var array Tasks to do at the end of the combat */
  public $onEnd = array();
  
  /**
   * @param \HeroesofAbenez\Entities\Team $team1 First team
   * @param \HeroesofAbenez\Entities\Team $team2 Second team
   */
  function __construct(Team $team1, Team $team2) {
    $this->round = 0;
    $this->team1 = $team1;
    $this->team2 = $team2;
    $this->log = new CombatLog;
    $this->onStart[] = array($this, "deployPets");
    $this->onEnd[] = array($this, "removeCombatEffects");
  }
  
  /**
   * Get winner of combat
   * 
   * @staticvar int $result
   * @return int Winning team/0
   */
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
   * Apply pet's effects to character at the start of the combat
   * 
   * @return void
   */
  function deployPets() {
    foreach($this->team1 as $character) {
      if($character->active_pet) {
        $effect = $character->getPet($character->active_pet)->deployParams;
        $character->addEffect(new CharacterEffect($effect));
      }
    }
    foreach($this->team2 as $character) {
      if($character->active_pet) {
        $effect = $character->getPet($character->active_pet)->deployParams;
        $character->addEffect(new CharacterEffect($effect));
      }
    }
  }
  
  /**
   * Remove combat effects from character at the end of the combat
   * 
   * @return void
   */
  function removeCombatEffects() {
    foreach($this->team1 as $character) {
      foreach($character->effects as $effect) {
        if($effect->duration === "combat" OR is_int($effect->duration)) $character->removeEffect($effect->id);
      }
    }
  }
  
  /**
   * Starts next round
   * 
   * @return int Winning team/0
   */
  protected function start_round() {
    $this->round++;
    $this->log->logText("Round $this->round");
    foreach($this->team1 as $character) {
      foreach($character->effects as $effect) {
     	if(is_int($effect->duration)) { $effect->duration--; }
      }
      $character->recalculateStats();
    }
    foreach($this->team2 as $character) {
      foreach($character->effects as $effect) {
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
    foreach($this->team1 as $character) {
      $character->recalculateStats();
    }
    foreach($this->team2 as $character) {
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
    $this->onStart();
    while($this->round < $this->round_limit) {
      if($this->start_round() > 0) break;
      if($this->end_round() > 0) break;
    }
    $this->onEnd();
    return $this->getWinner();
  }
}

/**
 * Combat log
 * 
 * @author Jakub Konečný
 */
class CombatLog extends \Nette\Object implements \Iterator {
  /** @var array */
  protected $actions = array();
  /** @var int */
  protected $pos;
  
  /**
   * Adds new entry
   * 
   * @param string $action
   * @param bool $result
   * @param int $amount
   * @param \HeroesofAbenez\Entities\Character $character1
   * @param \HeroesofAbenez\Entities\Character $character2
   * @param string $name
   */
  function log($action, $result, $amount, CharacterEntity $character1, CharacterEntity $character2, $name = "") {
    $this->actions[] = new CombatAction($action, $result, $amount, $character1, $character2, $name);
  }
  
  /**
   * Adds text entry
   * 
   * @param string $text
   * @return void
   */
  function logText($text) {
    $this->actions[] = (string) $text;
  }
  
  function rewind() {
    $this->pos = 0;
  }
  
  function current() {
    return $this->actions[$this->pos];
  }
  
  function key() {
    return $this->pos;
  }
  
  function next() {
    ++$this->pos;
  }
  
  function valid() {
    return isset($this->actions[$this->pos]);
  }
}
?>