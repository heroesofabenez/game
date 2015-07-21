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
 * @property-read \HeroesofAbenez\Model\CombatLog $log Log from the combat
 * @property-read int $winner
 * @method void onCombatStart() Tasks to do at the start of the combat
 * @method void onCombatEnd() Tasks to do at the end of the combat
 * @method void onRoundStart() Tasks to do at the start of a round
 * @method void onRoundEnd() Tasks to do at the end of a round
 * @method void onAttack(\HeroesofAbenez\Entities\Character $character1, \HeroesofAbenez\Entities\Character $character2) Tasks to do at attack
 * @method void onHeal(\HeroesofAbenez\Entities\Character $character1, \HeroesofAbenez\Entities\Character $character2) Tasks to do at healing
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
  /** @var array */
  protected $damage;
  /** @var array Tasks to do at the start of the combat */
  public $onCombatStart = array();
  /** @var array Tasks to do at the end of the combat */
  public $onCombatEnd = array();
  /** @var array Tasks to do at the start of a turn */
  public $onRoundStart = array();
  /** @var array Tasks to do at the end of a turn */
  public $onRoundEnd = array();
  /** @var array Tasks to do at attack */
  public $onAttack = array();
  /** @var array Tasks to do at healing */
  public $onHeal = array();
  /** @var array Temporary variable for results of an action */
  protected $results;
  
  /**
   * @param \HeroesofAbenez\Entities\Team $team1 First team
   * @param \HeroesofAbenez\Entities\Team $team2 Second team
   */
  function __construct(Team $team1, Team $team2) {
    $this->round = 0;
    $this->team1 = $team1;
    $this->team2 = $team2;
    $this->log = new CombatLog;
    $this->damage[1] = $this->damage[2] = 0;
    $this->onCombatStart[] = array($this, "deployPets");
    $this->onCombatEnd[] = array($this, "removeCombatEffects");
    $this->onRoundStart[] = array($this ,"recalculateStats");
    $this->onAttack[] = array($this, "attackHarm");
    $this->onAttack[] = array($this, "logDamage");
    $this->onAttack[] = array($this, "logResults");
    $this->onHeal[] = array($this, "heal");
    $this->onHeal[] = array($this, "logResults");
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
   * Log start of a round
   * 
   * @return void
   */
  function logRoundNumber() {
    $this->round++;
    $this->log->logText("Round $this->round");
  }
  
  function recalculateStats() {
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
  }
  
  /**
   * Starts next round
   * 
   * @return int Winning team/0
   */
  protected function start_round() {
    $this->logRoundNumber();
    $this->onRoundStart();
    if($this->getWinner() > 0) return $this->getWinner();
    return 0;
  }
  
  /**
   * Do a round
   * 
   * @return void
   */
  protected function do_round() {
    foreach($this->team1->activeMembers as $attacker) {
      $roll = rand(0, count($this->team2->aliveMembers) - 1);
      $defender = $this->team2->aliveMembers[$roll];
      $this->onAttack($attacker, $defender);
    }
    foreach($this->team2->activeMembers as $attacker) {
      $roll = rand(0, count($this->team1->aliveMembers) - 1);
      $defender = $this->team1->aliveMembers[$roll];
      $this->onAttack($attacker, $defender);
    }
  }
  
  /**
   * Ends round
   * 
   * @return int Winning team/0
   */
  protected function end_round() {
    $this->onRoundStart();
    if($this->getWinner() > 0) return $this->getWinner();
    return 0;
  }
  
  /**
   * Executes the combat
   * 
   * @return int Winning team
   */
  function execute() {
    $this->onCombatStart();
    while($this->round < $this->round_limit) {
      if($this->start_round() > 0) break;
      $this->do_round();
      if($this->end_round() > 0) break;
    }
    $this->onCombatEnd();
    return $this->getWinner();
  }
  
  /**
   * Do an attack
   * Hit chance = Attacker's hit - Defender's dodge, but at least 15%
   * Damage = Attacker's damage
   * 
   * @param \HeroesofAbenez\Entities\Character $character1 Attacker
   * @param \HeroesofAbenez\Entities\Character $character2 Defender
   */
  function attackHarm(CharacterEntity $character1, CharacterEntity $character2) {
    $result = array();
    $hit_chance = $character1->hit - $character2->dodge;
    if($hit_chance < 15) $hit_chance = 15;
    $roll = rand(0, 100);
    $result["result"] = ($roll <= $hit_chance);
    $result["amount"] = ($result["result"]) ? $character1->damage : 0;
    $character2->harm($result["amount"]);
    $result["action"] = "attack";
    $result["name"] = "";
    $this->results = $result;
  }
  
  /**
   * Heal a character
   * 
   * @param \HeroesofAbenez\Entities\Character $character1 Healer
   * @param \HeroesofAbenez\Entities\Character $character2 Wounded character
   */
  function heal(CharacterEntity $character1, CharacterEntity $character2) {
    $result = array();
    $hit_chance = $character1->intelligence * round($character1->level / 5) + 30;
    $roll = rand(0, 100);
    $result["result"] = ($roll <= $hit_chance);
    $amount = ($result["result"]) ? $character1->intelligence * 2 : 0;
    if($amount + $character2->hitpoints > $character2->max_hitpoints) {
      $amount = $character2->max_hitpoints - $character2->hitpoints;
    }
    $result["amount"] = $amount;
    $character2->heal($result["amount"]);
    $result["action"] = "healing";
    $result["name"] = "";
    $this->results = $result;
  }
  
  /**
   * Log results of an action
   * 
   * @param \HeroesofAbenez\Entities\Character $character1
   * @param \HeroesofAbenez\Entities\Character $character2
   */
  function logResults(CharacterEntity $character1, CharacterEntity $character2) {
    extract($this->results);
    $this->log->log($action, $result, $character1, $character2, $amount, $name);
    $this->results = NULL;
  }
  
  function logDamage(CharacterEntity $character1, CharacterEntity $character2) {
    $team = $this->team1->hasMember($character1->id) ? 1: 2;
    $this->damage[$team] += $this->results["amount"];
  }
  
  /**
   * @return \HeroesofAbenez\Model\CombatLog
   */
  function getLog() {
    return $this->log;
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
   * @param \HeroesofAbenez\Entities\Character $character1
   * @param \HeroesofAbenez\Entities\Character $character2
   * @param int $amount
   * @param string $name
   */
  function log($action, $result, CharacterEntity $character1, CharacterEntity $character2, $amount = 0, $name = "") {
    $this->actions[] = new CombatAction($action, $result, $character1, $character2, $amount, $name);
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