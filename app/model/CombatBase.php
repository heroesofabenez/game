<?php
namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Team,
    HeroesofAbenez\Entities\Character as CharacterEntity,
    HeroesofAbenez\Entities\CharacterEffect,
    HeroesofAbenez\Entities\CharacterSkillAttack,
    HeroesofAbenez\Entities\CharacterSkillSpecial;

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
 * @method void onSkillAttack(\HeroesofAbenez\Entities\Character $character1, \HeroesofAbenez\Entities\Character $character2, \HeroesofAbenez\Entities\CharacterSkillAttack $skill) Tasks to do at skill attack
 * @method void onSkillSpecial(\HeroesofAbenez\Entities\Character $character1, \HeroesofAbenez\Entities\Character $character2, \HeroesofAbenez\Entities\CharacterSkillSpecial $skill) Tasks to do when using special skill
 * @method void onHeal(\HeroesofAbenez\Entities\Character $character1, \HeroesofAbenez\Entities\Character $character2) Tasks to do at healing
 */
class CombatBase {
  use \Nette\SmartObject;
  
  const LOWEST_HP_THRESHOLD = 0.5;
  
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
  /** @var array Tasks to do at skill attack */
  public $onSkillAttack = [];
  /** @var array Tasks to do when using special skill */
  public $onSkillSpecial = [];
  /** @var array Tasks to do at healing */
  public $onHeal = [];
  /** @var array Temporary variable for results of an action */
  protected $results;
  
  function __construct(CombatLogger $logger) {
    $this->log = $logger;
    $this->onCombatStart[] = [$this, "deployPets"];
    $this->onCombatStart[] = [$this, "equipItems"];
    $this->onCombatStart[] = [$this, "setSkillsCooldowns"];
    $this->onCombatEnd[] = [$this, "removeCombatEffects"];
    $this->onCombatEnd[] = [$this, "logCombatResult"];
    $this->onRoundStart[] = [$this ,"recalculateStats"];
    $this->onRoundStart[] = [$this, "logRoundNumber"];
    $this->onRound[] = [$this, "mainStage"];
    $this->onRoundEnd[] = [$this, "decreaseSkillsCooldowns"];
    $this->onAttack[] = [$this, "attackHarm"];
    $this->onAttack[] = [$this, "logDamage"];
    $this->onAttack[] = [$this, "logResults"];
    $this->onSkillAttack[] = [$this, "useAttackSkill"];
    $this->onSkillAttack[] = [$this, "logDamage"];
    $this->onSkillAttack[] = [$this, "logResults"];
    $this->onSkillSpecial[] = [$this, "useSpecialSkill"];
    $this->onSkillSpecial[] = [$this, "logResults"];
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
   * @param CharacterEntity $character
   * @return int
   */
  protected function getTeam(CharacterEntity $character) {
    $team = $this->team1->hasMember($character->id) ? 1: 2;
    return $team;
  }
  
  /**
   * @param CharacterEntity $character
   * @return int
   */
  protected function getEnemyTeam(CharacterEntity $character) {
    $team = $this->team1->hasMember($character->id) ? 2: 1;
    return $team;
  }
  
  /**
   * Apply pet's effects to character at the start of the combat
   * 
   * @return void
   */
  function deployPets() {
    $characters = array_merge($this->team1->items, $this->team2->items);
    foreach($characters as $character) {
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
    $characters = array_merge($this->team1->items, $this->team2->items);
    foreach($characters as $character) {
      foreach($character->equipment as $item) {
        if($item->worn) $character->equipItem($item->id);
      }
    }
  }
  
  /**
   * Set skills' cooldowns
   * 
   * @return void
   */
  function setSkillsCooldowns() {
    $characters = array_merge($this->team1->items, $this->team2->items);
    foreach($characters as $character) {
      foreach($character->skills as $skill) {
        $skill->resetCooldown();
      }
    }
  }
  
  /**
   * Decrease skills' cooldowns
   * 
   * @return void
   */
  function decreaseSkillsCooldowns() {
    $characters = array_merge($this->team1->items, $this->team2->items);
    foreach($characters as $character) {
      foreach($character->skills as $skill) {
        $skill->decreaseCooldown();
      }
    }
  }
  
  /**
   * Remove combat effects from character at the end of the combat
   * 
   * @return void
   */
  function removeCombatEffects() {
    $characters = array_merge($this->team1->items, $this->team2->items);
    foreach($characters as $character) {
      foreach($character->effects as $effect) {
        if($effect->duration === "combat" OR is_int($effect->duration)) $character->removeEffect($effect->id);
      }
    }
  }
  
  /**
   * Mark primary character as used in this round
   * 
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
    $characters = array_merge($this->team1->items, $this->team2->items);
    foreach($characters as $character) {
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
  protected function findLowestHpCharacter(Team $team, $threshold = NULL) {
    $lowestHp = 9999;
    $lowestIndex = -1;
    if(is_null($threshold)) $threshold = static::LOWEST_HP_THRESHOLD;
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
   * @return CharacterEntity[]
   */
  protected function findHealers() {
    return [];
  }
  
  /**
   * @param CharacterEntity $character1
   * @param CharacterEntity $character2
   * @param CharacterSkillSpecial $skill
   * @return void
   */
  protected function doSpecialSkill(CharacterEntity $character1, CharacterEntity $character2, CharacterSkillSpecial $skill) {
    switch($skill->skill->target) {
      case "enemy":
        $this->onSkillSpecial($character1, $character2, $skill);
        break;
      case "self":
        $this->onSkillSpecial($character1, $character1, $skill);
        break;
      case "party":
        $team = $this->getTeam($character1);
        foreach($this->{"team". $team} as $target) $this->onSkillSpecial($character1, $target, $skill);
        break;
      case "enemy_party":
        $team = $this->getEnemyTeam($character1);
        foreach($this->{"team". $team} as $target) $this->onSkillSpecial($character1, $target, $skill);
        break;
    }
  }
  
  /**
   * Main stage of a round
   * 
   * @return void
   */
  function mainStage() {
    $characters = array_merge($this->team1->usableMembers, $this->team2->usableMembers);
    foreach($characters as $character) {
      if($character->hitpoints < 1) continue;
      $team = $this->getTeam($character);
      $enemyTeam = $this->getEnemyTeam($character);
      if(in_array($character, $this->findHealers())) {
        $target = $this->selectHealingTarget($character, $this->{"team" . $team});
        if($target) {
          $this->onHeal($character, $target);
          continue;
        }
      }
      $target = $this->selectAttackTarget($character, $this->{"team" . $enemyTeam});
      if(is_null($target)) break;
      if(count($character->usableSkills)) {
        $skill = $character->usableSkills[0];
        if($skill instanceof CharacterSkillAttack) {
          for($i = 1; $i <= $skill->skill->strikes; $i++) $this->onSkillAttack($character, $target, $skill);
        } else {
          $this->doSpecialSkill($character, $target, $skill);
        }
      } else {
        $this->onAttack($character, $target);
      }
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
   * Calculate hit chance for attack/skill attack
   * 
   * @param CharacterEntity $character1
   * @param CharacterEntity $character2
   * @param CharacterSkillAttack $skill
   * @return int
   */
  protected function calculateHitChance(CharacterEntity $character1, CharacterEntity $character2, CharacterSkillAttack $skill = NULL) {
    if($skill) $hit_chance = ($character1->hit / 100 * $skill->hitRate) - $character2->dodge;
    else $hit_chance = $character1->hit - $character2->dodge;
    if($hit_chance < 15) $hit_chance = 15;
    if($hit_chance > 100) $hit_chance = 100;
    return $hit_chance;
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
    $hit_chance = $this->calculateHitChance($character1, $character2);
    $roll = rand(0, 100);
    $result["result"] = ($roll <= $hit_chance);
    if($result["result"]) $result["amount"] = (int) $character1->damage - $character2->defense;
    else $result["amount"] = 0;
    if($result["amount"] < 0) $result["amount"] = 0;
    if($character2->hitpoints - $result["amount"] < 0) {
      $result["amount"] = $character2->hitpoints;
    }
    if($result["amount"]) $character2->harm($result["amount"]);
    $result["action"] = "attack";
    $result["name"] = "";
    $this->results = $result;
  }
  
  /**
   * Use an attack skill
   * 
   * @param CharacterEntity $character1 Attacker
   * @param CharacterEntity $character2 Defender
   * @param CharacterSkillAttack $skill Used skill
   */
  function useAttackSkill(CharacterEntity $character1, CharacterEntity $character2, CharacterSkillAttack $skill) {
    $result = [];
    $hit_chance = $this->calculateHitChance($character1, $character2, $skill);
    $roll = rand(0, 100);
    $result["result"] = ($roll <= $hit_chance);
    if($result["result"]) {
      $result["amount"] = $character1->damage - $character2->defense;
      $result["amount"] = (int) ($result["amount"] / 100 * $skill->damage);
    } else {
      $result["amount"] = 0;
    }
    if($character2->hitpoints - $result["amount"] < 0) {
      $result["amount"] = $character2->hitpoints;
    }
    if($result["amount"]) $character2->harm($result["amount"]);
    $result["action"] = "skill_attack";
    $result["name"] = $skill->skill->name;
    $this->results = $result;
    $skill->resetCooldown();
  }
  
  /**
   * Use a special skill
   * 
   * @param CharacterEntity $character1
   * @param CharacterEntity $character2
   * @param CharacterSkillAttack $skill
   * @return void
   */
  function useSpecialSkill(CharacterEntity $character1, CharacterEntity $character2, CharacterSkillSpecial $skill) {
    $result = [];
    $result["result"] = true;
    $result["amount"] = 0;
    $result["action"] = "skill_special";
    $result["name"] = $skill->skill->name;
    $this->results = $result;
    $effect = [
      "id" => "skill{$skill->skill->id}Effect",
      "type" => $skill->skill->type,
      "stat" => ($skill->skill->type === "stun"? NULL : $skill->skill->stat),
      "value" => $skill->value,
      "source" => "skill",
      "duration" => $skill->skill->duration
    ];
    $character2->addEffect(new CharacterEffect($effect));
    $skill->resetCooldown();
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
