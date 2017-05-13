<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Team,
    HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Entities\CharacterEffect,
    HeroesofAbenez\Entities\CharacterSkillAttack,
    HeroesofAbenez\Entities\CharacterSkillSpecial,
    HeroesofAbenez\Utils\InvalidStateException;

/**
 * Handles combat
 * 
 * @author Jakub Konečný
 * @property-read CombatLogger $log Log from the combat
 * @property-read int $winner
 * @property-read int $round
 * @property callable $victoryCondition To evaluate the winner of combat. Gets CombatBase as first parameter, should return winning team (1/2) or 0 if there is not winner (yet)
 * @method void onCombatStart() Tasks to do at the start of the combat
 * @method void onCombatEnd() Tasks to do at the end of the combat
 * @method void onRoundStart() Tasks to do at the start of a round
 * @method void onRound() Tasks to do during a round
 * @method void onRoundEnd() Tasks to do at the end of a round
 * @method void onAttack(Character $attacker, Character $defender) Tasks to do at attack
 * @method void onSkillAttack(Character $attacker, Character $defender, CharacterSkillAttack $skill) Tasks to do at skill attack
 * @method void onSkillSpecial(Character $character1, Character $target, CharacterSkillSpecial $skill) Tasks to do when using special skill
 * @method void onHeal(Character $healer, Character $patient) Tasks to do at healing
 */
class CombatBase {
  use \Nette\SmartObject;
  
  const LOWEST_HP_THRESHOLD = 0.5;
  
  /** @var Team First team */
  protected $team1;
  /** @var Team Second team */
  protected $team2;
  /** @var CombatLogger */
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
  /** @var array|NULL Temporary variable for results of an action */
  protected $results;
  /** @var callable */
  protected $victoryCondition;
  
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
    $this->onRoundEnd[] = [$this, "resetInitiative"];
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
    $this->victoryCondition = [$this, "victoryConditionMoreDamage"];
  }
  
  function getRound(): int {
    return $this->round;
  }
  
  /**
   * Set teams
   * 
   * @param Team $team1
   * @param Team $team2
   * @return void
   */
  function setTeams(Team $team1, Team $team2): void {
    if($this->team1) {
      throw new ImmutableException("Teams has already been set.");
    }
    $this->team1 = & $team1;
    $this->team2 = & $team2;
    $this->log->setTeams($team1, $team2);
  }
  
  /**
   * @return callable
   */
  function getVictoryCondition(): callable {
    return $this->victoryCondition;
  }
  
  /**
   * @param callable $victoryCondition
   */
  function setVictoryCondition(callable $victoryCondition) {
    $this->victoryCondition = $victoryCondition;
  }
  
  /**
   * Evaluate winner of combat
   * The team which dealt more damage after round limit, wins
   * If all members of one team are eliminated before that, the other team wins
   *
   * @param CombatBase $combat
   * @return int
   */
  function victoryConditionMoreDamage(CombatBase $combat): int {
    $result = 0;
    if($combat->round <= $combat->round_limit) {
      if(!$combat->team1->hasAliveMembers()) {
        $result = 2;
      } elseif(!$combat->team2->hasAliveMembers()) {
        $result = 1;
      }
    } elseif($combat->round > $combat->round_limit) {
      $result = ($combat->damage[1] > $combat->damage[2]) ? 1 : 2;
    }
    return $result;
  }
  
  /**
   * Evaluate winner of combat
   * Team 1 wins only if they eliminate all opponents before round limit
   *
   * @param CombatBase $combat
   * @return int
   */
  function victoryConditionEliminateSecondTeam(CombatBase $combat): int {
    $result = 0;
    if($combat->round <= $combat->round_limit) {
      if(!$combat->team1->hasAliveMembers()) {
        $result = 2;
      } elseif(!$combat->team2->hasAliveMembers()) {
        $result = 1;
      }
    } elseif($combat->round > $combat->round_limit) {
      $result = (!$combat->team2->hasAliveMembers()) ? 1 : 2;
    }
    return $result;
  }
  
  /**
   * Evaluate winner of combat
   * Team 1 wins if at least 1 of its members is alive after round limit
   *
   * @param CombatBase $combat
   * @return int
   */
  function victoryConditionFirstTeamSurvives(CombatBase $combat): int {
    $result = 0;
    if($combat->round <= $combat->round_limit) {
      if(!$combat->team1->hasAliveMembers()) {
        $result = 2;
      } elseif(!$combat->team2->hasAliveMembers()) {
        $result = 1;
      }
    } elseif($combat->round > $combat->round_limit) {
      $result = ($combat->team1->hasAliveMembers()) ? 1 : 2;
    }
    return $result;
  }
  
  /**
   * Get winner of combat
   * 
   * @staticvar int $result
   * @return int Winning team/0
   */
  function getWinner(): int {
    static $result = 0;
    if($result === 0) {
      $result = call_user_func($this->victoryCondition, $this);
    }
    return $result;
  }
  
  /**
   * @param Character $character
   * @return int
   */
  protected function getTeam(Character $character): int {
    $team = $this->team1->hasMember($character->id) ? 1 : 2;
    return $team;
  }
  
  /**
   * @param Character $character
   * @return int
   */
  protected function getEnemyTeam(Character $character): int {
    $team = $this->team1->hasMember($character->id) ? 2 : 1;
    return $team;
  }
  
  /**
   * Apply pet's effects to character at the start of the combat
   * 
   * @return void
   */
  function deployPets(): void {
    $characters = array_merge($this->team1->items, $this->team2->items);
    /** @var Character $character */
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
  function equipItems(): void {
    $characters = array_merge($this->team1->items, $this->team2->items);
    /** @var Character $character */
    foreach($characters as $character) {
      foreach($character->equipment as $item) {
        if($item->worn) {
          $character->equipItem($item->id);
        }
      }
    }
  }
  
  /**
   * Set skills' cooldowns
   * 
   * @return void
   */
  function setSkillsCooldowns(): void {
    $characters = array_merge($this->team1->items, $this->team2->items);
    /** @var Character $character */
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
  function decreaseSkillsCooldowns(): void {
    $characters = array_merge($this->team1->items, $this->team2->items);
    /** @var Character $character */
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
  function removeCombatEffects(): void {
    $characters = array_merge($this->team1->items, $this->team2->items);
    /** @var Character $character */
    foreach($characters as $character) {
      foreach($character->effects as $effect) {
        if($effect->duration === "combat" OR is_int($effect->duration)) {
          $character->removeEffect($effect->id);
        }
      }
    }
  }
  
  /**
   * Add winner to the log
   * 
   * @return void
   */
  function logCombatResult(): void {
    $this->log->round = 5000;
    $text = "Combat ends. {$this->team1->name} dealt {$this->damage[1]} damage, {$this->team2->name} dealt {$this->damage[2]} damage. ";
    if($this->getWinner() === 1) {
      $text .= $this->team1->name;
    } else {
      $text .= $this->team2->name;
    }
    $text .= " wins.";
    $this->log->logText($text);
  }
  
  /**
   * Log start of a round
   * 
   * @return void
   */
  function logRoundNumber(): void {
    $this->log->round = ++$this->round;
  }
  
  /**
   * Decrease duration of effects and recalculate stats
   * 
   * @return void
   */
  function recalculateStats(): void {
    $characters = array_merge($this->team1->items, $this->team2->items);
    /** @var Character $character */
    foreach($characters as $character) {
      $character->recalculateStats();
      if($character->hitpoints > 0) {
        $character->calculateInitiative();
      }
    }
  }
  
  /**
   * Reset characters' initiative
   * 
   * @return void
   */
  function resetInitiative(): void {
    $characters = array_merge($this->team1->items, $this->team2->items);
    /** @var Character $character */
    foreach($characters as $character) {
      $character->resetInitiative();
    }
  }
  
  /**
   * Select random character of the team
   * 
   * @param Team $team
   * @return Character|NULL
   */
  protected function selectRandomCharacter(Team $team): ?Character {
    if(count($team->aliveMembers) === 0) {
      return NULL;
    }
    $roll = rand(0, count($team->aliveMembers) - 1);
    return $team->aliveMembers[$roll];
  }
  
  /**
   * Select target for attack
   * 
   * @param Character $attacker
   * @return Character|NULL
   */
  protected function selectAttackTarget(Character $attacker): ?Character {
    $enemyTeam = $this->getEnemyTeam($attacker);
    return $this->selectRandomCharacter($this->{"team" . $enemyTeam});
  }
  
  /**
   * Find character with lowest hp in the team
   * 
   * @param Team $team
   * @param int $threshold
   * @return Character|NULL
   */
  // @codingStandardsIgnoreLine
  protected function findLowestHpCharacter(Team $team, int $threshold = NULL): ?Character {
    $lowestHp = 9999;
    $lowestIndex = -1;
    if(is_null($threshold)) {
      $threshold = static::LOWEST_HP_THRESHOLD;
    }
    foreach($team->aliveMembers as $index => $member) {
      if($member->hitpoints <= $member->max_hitpoints * $threshold AND $member->hitpoints < $lowestHp) {
        $lowestHp = $member->hitpoints;
        $lowestIndex = $index;
      }
    }
    if($lowestIndex === -1) {
      return NULL;
    } else {
      return $team->aliveMembers[$lowestIndex];
    }
  }
  
  /**
   * Select target for healing
   * 
   * @param Character $healer
   * @return Character|NULL
   */
  protected function selectHealingTarget(Character $healer): ?Character {
    $team = $this->getTeam($healer);
    return $this->findLowestHpCharacter($this->{"team" . $team});
  }
  
  /**
   * @return Character[]
   */
  protected function findHealers(): array {
    return [];
  }
  
  /**
   * @param Character $character1
   * @param Character $character2
   * @param CharacterSkillSpecial $skill
   * @return void
   */
  protected function doSpecialSkill(Character $character1, Character $character2, CharacterSkillSpecial $skill): void {
    switch($skill->skill->target) {
      case "enemy":
        $this->onSkillSpecial($character1, $character2, $skill);
        break;
      case "self":
        $this->onSkillSpecial($character1, $character1, $skill);
        break;
      case "party":
        $team = $this->getTeam($character1);
        foreach($this->{"team". $team} as $target) {
          $this->onSkillSpecial($character1, $target, $skill);
        }
        break;
      case "enemy_party":
        $team = $this->getEnemyTeam($character1);
        foreach($this->{"team". $team} as $target) {
          $this->onSkillSpecial($character1, $target, $skill);
        }
        break;
    }
  }
  
  /**
   * Main stage of a round
   * 
   * @return void
   */
  function mainStage(): void {
    $characters = array_merge($this->team1->usableMembers, $this->team2->usableMembers);
    usort($characters, function(Character $a, Character $b) {
      return -1 * strcmp((string) $a->initiative, (string) $b->initiative);
    });
    foreach($characters as $character) {
      if($character->hitpoints < 1) {
        continue;
      }
      if(in_array($character, $this->findHealers())) {
        $target = $this->selectHealingTarget($character);
        if($target) {
          $this->onHeal($character, $target);
          continue;
        }
      }
      $target = $this->selectAttackTarget($character);
      if(is_null($target)) {
        break;
      }
      if(count($character->usableSkills)) {
        $skill = $character->usableSkills[0];
        if($skill instanceof CharacterSkillAttack) {
          for($i = 1; $i <= $skill->skill->strikes; $i++) {
            $this->onSkillAttack($character, $target, $skill);
          }
        } else {
          $this->doSpecialSkill($character, $target, $skill);
        }
      } else {
        $this->onAttack($character, $target);
      }
    }
  }
  
  /**
   * Start next round
   * 
   * @return int Winning team/0
   */
  protected function startRound(): int {
    $this->onRoundStart();
    if($this->getWinner() > 0) {
      return $this->getWinner();
    } else {
      return 0;
    }
  }
  
  /**
   * Do a round
   * 
   * @return void
   */
  protected function doRound(): void {
    $this->onRound();
  }
  
  /**
   * End round
   * 
   * @return int Winning team/0
   */
  protected function endRound(): int {
    $this->onRoundEnd();
    if($this->getWinner() > 0) {
      return $this->getWinner();
    } else {
      return 0;
    }
  }
  
  /**
   * Executes the combat
   * 
   * @return int Winning team
   */
  function execute(): int {
    if(!$this->team1) {
      throw new InvalidStateException("Teams are not set.");
    }
    $this->onCombatStart();
    while($this->round <= $this->round_limit) {
      if($this->startRound() > 0) {
        break;
      }
      $this->doRound();
      if($this->endRound() > 0) {
        break;
      }
    }
    $this->onCombatEnd();
    return $this->getWinner();
  }
  
  /**
   * Calculate hit chance for attack/skill attack
   * 
   * @param Character $character1
   * @param Character $character2
   * @param CharacterSkillAttack $skill
   * @return int
   */
  protected function calculateHitChance(Character $character1, Character $character2, CharacterSkillAttack $skill = NULL): int {
    if($skill) {
      $hit_chance = ($character1->hit / 100 * $skill->hitRate) - $character2->dodge;
    } else {
      $hit_chance = $character1->hit - $character2->dodge;
    }
    if($hit_chance < 15) {
      $hit_chance = 15;
    } elseif($hit_chance > 100) {
      $hit_chance = 100;
    }
    return (int) $hit_chance;
  }
  
  /**
   * Do an attack
   * Hit chance = Attacker's hit - Defender's dodge, but at least 15%
   * Damage = Attacker's damage
   * 
   * @param Character $attacker
   * @param Character $defender
   * @return void
   */
  function attackHarm(Character $attacker, Character $defender): void {
    $result = [];
    $hit_chance = $this->calculateHitChance($attacker, $defender);
    $roll = rand(0, 100);
    $result["result"] = ($roll <= $hit_chance);
    if($result["result"]) {
      $result["amount"] = (int) $attacker->damage - $defender->defense;
    } else {
      $result["amount"] = 0;
    }
    if($result["amount"] < 0) {
      $result["amount"] = 0;
    }
    if($defender->hitpoints - $result["amount"] < 0) {
      $result["amount"] = $defender->hitpoints;
    }
    if($result["amount"]) {
      $defender->harm($result["amount"]);
    }
    $result["action"] = "attack";
    $result["name"] = "";
    $this->results = $result;
  }
  
  /**
   * Use an attack skill
   * 
   * @param Character $attacker
   * @param Character $defender
   * @param CharacterSkillAttack $skill Used skill
   * @return void
   */
  function useAttackSkill(Character $attacker, Character $defender, CharacterSkillAttack $skill): void {
    $result = [];
    $hit_chance = $this->calculateHitChance($attacker, $defender, $skill);
    $roll = rand(0, 100);
    $result["result"] = ($roll <= $hit_chance);
    if($result["result"]) {
      $result["amount"] = $attacker->damage - $defender->defense;
      $result["amount"] = (int) ($result["amount"] / 100 * $skill->damage);
    } else {
      $result["amount"] = 0;
    }
    if($defender->hitpoints - $result["amount"] < 0) {
      $result["amount"] = $defender->hitpoints;
    }
    if($result["amount"]) {
      $defender->harm($result["amount"]);
    }
    $result["action"] = "skill_attack";
    $result["name"] = $skill->skill->name;
    $this->results = $result;
    $skill->resetCooldown();
  }
  
  /**
   * Use a special skill
   * 
   * @param Character $character1
   * @param Character $target
   * @param CharacterSkillSpecial $skill
   * @return void
   */
  function useSpecialSkill(Character $character1, Character $target, CharacterSkillSpecial $skill): void {
    $result = [
      "result" => true, "amount" => 0, "action" => "skill_special", "name" => $skill->skill->name
    ];
    $this->results = $result;
    $effect = [
      "id" => "skill{$skill->skill->id}Effect",
      "type" => $skill->skill->type,
      "stat" => (($skill->skill->type === "stun") ? NULL : $skill->skill->stat),
      "value" => $skill->value,
      "source" => "skill",
      "duration" => $skill->skill->duration
    ];
    $target->addEffect(new CharacterEffect($effect));
    $skill->resetCooldown();
  }
  
  /**
   * Heal a character
   * 
   * @param Character $healer
   * @param Character $patient
   * @return void
   */
  function heal(Character $healer, Character $patient): void {
    $result = [];
    $hit_chance = $healer->intelligence * round($healer->level / 5) + 30;
    $roll = rand(0, 100);
    $result["result"] = ($roll <= $hit_chance);
    $amount = ($result["result"]) ? $healer->intelligence / 2 : 0;
    if($amount + $patient->hitpoints > $patient->max_hitpoints) {
      $amount = $patient->max_hitpoints - $patient->hitpoints;
    }
    $result["amount"] = (int) $amount;
    if($result["amount"]) {
      $patient->heal($result["amount"]);
    }
    $result["action"] = "healing";
    $result["name"] = "";
    $this->results = $result;
  }
  
  /**
   * Log results of an action
   * 
   * @param Character $character1
   * @param Character $character2
   * @return void
   */
  function logResults(Character $character1, Character $character2): void {
    $results = $this->results;
    $this->log->log($results["action"], $results["result"], $character1, $character2, $results["amount"], $results["name"]);
    $this->results = NULL;
  }
  
  /**
   * Log dealt damage
   * 
   * @param Character $attacker
   * @param Character $defender
   * @return void
   */
  function logDamage(Character $attacker, Character $defender): void {
    $team = $this->team1->hasMember($attacker->id) ? 1 : 2;
    $this->damage[$team] += $this->results["amount"];
  }
  
  /**
   * @return CombatLogger
   */
  function getLog(): CombatLogger {
    return $this->log;
  }
}
?>