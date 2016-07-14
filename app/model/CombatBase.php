<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Team,
    HeroesofAbenez\Entities\Character as CharacterEntity,
    HeroesofAbenez\Entities\CharacterEffect;

/**
 * Handles combat
 * 
 * @author Jakub Konečný
 * @property-read \HeroesofAbenez\Model\CombatLogger $log Log from the combat
 * @property-read int $winner
 * @property-read in $round
 * @method void onCombatStart() Tasks to do at the start of the combat
 * @method void onCombatEnd() Tasks to do at the end of the combat
 * @method void onRoundStart() Tasks to do at the start of a round
 * @method void onRound() Tasks to do during a round
 * @method void onRoundEnd() Tasks to do at the end of a round
 * @method void onAttack(\HeroesofAbenez\Entities\Character $character1, \HeroesofAbenez\Entities\Character $character2) Tasks to do at attack
 * @method void onHeal(\HeroesofAbenez\Entities\Character $character1, \HeroesofAbenez\Entities\Character $character2) Tasks to do at healing
 */
class CombatBase {
  use \Nette\SmartObject;
  
  /** @var \HeroesofAbenez\Entities\Team First team */
  protected $team1;
  /** @var \HeroesofAbenez\Entities\Team Second team */
  protected $team2;
  /** @var \HeroesofAbenez\Model\CombatLogger */
  protected $log;
  /** @var int Number of current round */
  protected $round = 0;
  /** @var int Round limit */
  protected $round_limit = 30;
  /** @var array Dealt damage by team */
  protected $damage = [1 => 0, 2 => 0];
  /** @var array Tasks to do at the start of the combat */
  public $onCombatStart = [];
  /** @var array Tasks to do at the end of the combat */
  public $onCombatEnd = [];
  /** @var array Tasks to do at the start of a turn */
  public $onRoundStart = [];
  /** @var array Tasks to do during a round */
  public $onRound = [];
  /** @var array Tasks to do at the end of a turn */
  public $onRoundEnd = [];
  /** @var array Tasks to do at attack */
  public $onAttack = [];
  /** @var array Tasks to do at healing */
  public $onHeal = [];
  /** @var array Temporary variable for results of an action */
  protected $results;
  
  function __construct(CombatLogger $logger) {
    $this->log = $logger;
    $this->onCombatStart[] = [$this, "deployPets"];
    $this->onCombatStart[] = [$this, "equipItems"];
    $this->onCombatEnd[] = [$this, "removeCombatEffects"];
    $this->onCombatEnd[] = [$this, "logCombatResult"];
    $this->onRoundStart[] = [$this ,"recalculateStats"];
    $this->onRoundStart[] = [$this, "logRoundNumber"];
    $this->onRound[] = [$this, "doHealing"];
    $this->onRound[] = [$this, "doAttacks"];
    $this->onRoundEnd[] = [$this, "clearUsed"];
    $this->onAttack[] = [$this, "attackHarm"];
    $this->onAttack[] = [$this, "logDamage"];
    $this->onAttack[] = [$this, "logResults"];
    $this->onAttack[] = [$this, "markUsed"];
    $this->onHeal[] = [$this, "heal"];
    $this->onHeal[] = [$this, "logResults"];
    $this->onHeal[] = [$this, "markUsed"];
  }
  
  function getRound() {
    return $this->round;
  }
  
  /**
   * Set teams
   * 
   * @param Team $team1
   * @param Team $team2
   * @return void
   */
  function setTeams(Team $team1, Team $team2) {
    if($this->team1) throw new ImmutableException("Teams has already been set.");
    $this->team1 = & $team1;
    $this->team2 = & $team2;
    $this->log->setTeams($team1, $team2);
  }
  
  /**
   * Get winner of combat
   * 
   * @staticvar int $result
   * @return int Winning team/0
   */
  function getWinner() {
    static $result = 0;
    if($this->round > $this->round_limit AND $result === 0) {
      $result = $this->damage[1] > $this->damage[2] ? 1: 2;
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
   * Apply effects from worn items
   * 
   * @return void
   */
  function equipItems() {
    foreach($this->team1 as $character) {
      foreach($character->equipment as $item) {
        if($item->worn) $character->equipItem($item->id);
      }
    }
    foreach($this->team2 as $character) {
      foreach($character->equipment as $item) {
        if($item->worn) $character->equipItem($item->id);
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
    foreach($this->team2 as $character) {
      foreach($character->effects as $effect) {
        if($effect->duration === "combat" OR is_int($effect->duration)) $character->removeEffect($effect->id);
      }
    }
  }
  
  /**
   * Mark primary character as used in this round
   * @param CharacterEntity $character1
   * @param CharacterEntity $character2
   * @return void
   */
  function markUsed(CharacterEntity $character1, CharacterEntity $character2) {
    $team = "team1";
    $index = $this->team1->getIndex($character1->id);
    if($index === -1) {
      $index = $this->team2->getIndex($character1->id);
      $team = "team2";
    }
    $this->$team->useMember($index);
  }
  
  /**
   * Clear lists of used team members
   * 
   * @return void
   */
  function clearUsed() {
    $this->team1->clearUsed();
    $this->team2->clearUsed();
  }
  
  /**
   * Add winner to the log
   * 
   * @return void
   */
  function logCombatResult() {
    $this->log->round = 5000;
    $text = "Combat ends. {$this->team1->name} dealt {$this->damage[1]} damage, {$this->team2->name} dealt {$this->damage[2]} damage. ";
    if($this->getWinner() === 1) $text .= $this->team1->name;
    else $text .= $this->team2->name;
    $text .= " wins.";
    $this->log->logText($text);
  }
  
  /**
   * Log start of a round
   * 
   * @return void
   */
  function logRoundNumber() {
    $this->round++;
    $this->log->round = $this->round;
  }
  
  /**
   * Decrease duration of effects and recalculate stats
   * 
   * @return void
   */
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
   * Select random character of the team
   * 
   * @param Team $team
   * @return CharacterEntity|NULL
   */
  protected function selectRandomCharacter(Team $team) {
    if(count($team->aliveMembers) === 0) return NULL;
    $roll = rand(0, count($team->aliveMembers) - 1);
    return $team->aliveMembers[$roll];
  }
  
  /**
   * Select target for attack
   * 
   * @param CharacterEntity $attacker
   * @param Team $opponents
   * @return CharacterEntity|NULL
   */
  protected function selectAttackTarget(CharacterEntity $attacker, Team $opponents) {
    return $this->selectRandomCharacter($opponents);
  }
  
  /**
   * Find character with lowest hp in the team
   * 
   * @param Team $team
   * @param int $threshold
   * @return CharacterEntity|NULL
   */
  protected function findLowestHpCharacter(Team $team, $threshold = 0.5) {
    $lowestHp = 9999;
    $lowestIndex = -1;
    foreach($team->aliveMembers as $index => $member) {
      if($member->hitpoints <= $member->max_hitpoints * $threshold AND $member->hitpoints < $lowestHp) {
        $lowestHp = $member->hitpoints;
        $lowestIndex = $index;
      }
    }
    if($lowestIndex === -1) return NULL;
    else return $team->aliveMembers[$lowestIndex];
  }
  
  /**
   * Select target for healing
   * 
   * @param CharacterEntity $healer
   * @param Team $team
   * @return CharacterEntity|NULL
   */
  protected function selectHealingTarget(CharacterEntity $healer, Team $team) {
    return $this->findLowestHpCharacter($team);
  }
  
  /**
   * @return void
   */
  function doHealing() {
    
  }
  
  /**
   * @return void
   */
  function doAttacks() {
    foreach($this->team1->usableMembers as $index => $attacker) {
      $target = $this->selectAttackTarget($attacker, $this->team2);
      if(is_null($target)) break; else $this->onAttack($attacker, $target);
    }
    foreach($this->team2->usableMembers as $index => $attacker) {
      $target = $this->selectAttackTarget($attacker, $this->team1);
      if(is_null($target)) break; else $this->onAttack($attacker, $target);
    }
  }
  
  /**
   * Starts next round
   * 
   * @return int Winning team/0
   */
  protected function start_round() {
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
    $this->onRound();
    
  }
  
  /**
   * Ends round
   * 
   * @return int Winning team/0
   */
  protected function end_round() {
    $this->onRoundEnd();
    if($this->getWinner() > 0) return $this->getWinner();
    return 0;
  }
  
  /**
   * Executes the combat
   * 
   * @return int Winning team
   */
  function execute() {
    if(!$this->team1) throw new InvalidStateException("Teams are not set.");
    $this->onCombatStart();
    while($this->round <= $this->round_limit) {
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
    $result = [];
    $hit_chance = $character1->hit - $character2->dodge;
    if($hit_chance < 15) $hit_chance = 15;
    if($hit_chance > 100) $hit_chance = 100;
    $roll = rand(0, 100);
    $result["result"] = ($roll <= $hit_chance);
    if($result["result"]) $result["amount"] = (int) $character1->damage - $character2->defense;
    else $result["amount"] = 0;
    if($character2->hitpoints - $result["amount"] < 0) {
      $result["amount"] = $character2->hitpoints;
    }
    if($result["amount"]) $character2->harm($result["amount"]);
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
    $result = [];
    $hit_chance = $character1->intelligence * round($character1->level / 5) + 30;
    $roll = rand(0, 100);
    $result["result"] = ($roll <= $hit_chance);
    $amount = ($result["result"]) ? $character1->intelligence / 2 : 0;
    if($amount + $character2->hitpoints > $character2->max_hitpoints) {
      $amount = $character2->max_hitpoints - $character2->hitpoints;
    }
    $result["amount"] = $amount;
    if($result["amount"]) $character2->heal($result["amount"]);
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
  
  /**
   * Log dealt damage
   * 
   * @param CharacterEntity $character1
   * @param CharacterEntity $character2
   * @return void
   */
  function logDamage(CharacterEntity $character1, CharacterEntity $character2) {
    $team = $this->team1->hasMember($character1->id) ? 1: 2;
    $this->damage[$team] += $this->results["amount"];
  }
  
  /**
   * @return \HeroesofAbenez\Model\CombatLogger
   */
  function getLog() {
    return $this->log;
  }
}
?>
