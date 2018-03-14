<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Entities\Team,
    HeroesofAbenez\Entities\Character,
    HeroesofAbenez\Entities\CharacterEffect,
    HeroesofAbenez\Orm\CharacterAttackSkillDummy,
    HeroesofAbenez\Orm\CharacterSpecialSkillDummy,
    HeroesofAbenez\Utils\InvalidStateException,
    HeroesofAbenez\Orm\SkillSpecial,
    HeroesofAbenez\Utils\Numbers;

/**
 * Handles combat
 * 
 * @author Jakub Konečný
 * @property-read CombatLogger $log Log from the combat
 * @property-read int $winner
 * @property-read int $round
 * @property callable $victoryCondition To evaluate the winner of combat. Gets combat, team1 and team2 as parameters, should return winning team (1/2) or 0 if there is not winner (yet)
 * @property callable $healers To determine characters that are supposed to heal their team. Gets team1 and team2 as parameters, should return Team
 * @method void onCombatStart()
 * @method void onCombatEnd()
 * @method void onRoundStart()
 * @method void onRound()
 * @method void onRoundEnd()
 * @method void onAttack(Character $attacker, Character $defender)
 * @method void onSkillAttack(Character $attacker, Character $defender, CharacterAttackSkillDummy $skill)
 * @method void onSkillSpecial(Character $character1, Character $target, CharacterSpecialSkillDummy $skill)
 * @method void onHeal(Character $healer, Character $patient)
 */
class CombatBase {
  use \Nette\SmartObject;
  
  protected const LOWEST_HP_THRESHOLD = 0.5;
  
  /** @var Team First team */
  protected $team1;
  /** @var Team Second team */
  protected $team2;
  /** @var CombatLogger */
  protected $log;
  /** @var int Number of current round */
  protected $round = 0;
  /** @var int Round limit */
  protected $roundLimit = 30;
  /** @var array Dealt damage by team */
  protected $damage = [1 => 0, 2 => 0];
  /** @var callable[] */
  public $onCombatStart = [];
  /** @var callable[] */
  public $onCombatEnd = [];
  /** @var callable[] */
  public $onRoundStart = [];
  /** @var callable[] */
  public $onRound = [];
  /** @var callable[] */
  public $onRoundEnd = [];
  /** @var callable[] */
  public $onAttack = [];
  /** @var callable[] */
  public $onSkillAttack = [];
  /** @var callable[] */
  public $onSkillSpecial = [];
  /** @var callable[] */
  public $onHeal = [];
  /** @var array|NULL Temporary variable for results of an action */
  protected $results;
  /** @var callable */
  protected $victoryCondition;
  /** @var callable */
  protected $healers;
  
  public function __construct(CombatLogger $logger) {
    $this->log = $logger;
    $this->onCombatStart[] = [$this, "deployPets"];
    $this->onCombatStart[] = [$this, "equipItems"];
    $this->onCombatStart[] = [$this, "setSkillsCooldowns"];
    $this->onCombatEnd[] = [$this, "removeCombatEffects"];
    $this->onCombatEnd[] = [$this, "logCombatResult"];
    $this->onCombatEnd[] = [$this, "resetInitiative"];
    $this->onRoundStart[] = [$this ,"recalculateStats"];
    $this->onRoundStart[] = [$this, "logRoundNumber"];
    $this->onRoundStart[] = [$this, "applyPoison"];
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
    $this->healers = function(): Team {
      return new Team("healers");
    };
  }
  
  public function getRound(): int {
    return $this->round;
  }
  
  /**
   * Set teams
   */
  public function setTeams(Team $team1, Team $team2): void {
    if(isset($this->team1)) {
      throw new ImmutableException("Teams has already been set.");
    }
    $this->team1 = & $team1;
    $this->team2 = & $team2;
    $this->log->setTeams($team1, $team2);
  }
  
  /**
   * Set participants for duel
   * Creates teams named after the member
   */
  public function setDuelParticipants(Character $player, Character $opponent): void {
    $team1 = new Team($player->name);
    $team1[] = $player;
    $team2 = new Team($opponent->name);
    $team2[] = $opponent;
    $this->setTeams($team1, $team2);
  }
  
  public function getVictoryCondition(): callable {
    return $this->victoryCondition;
  }
  
  public function setVictoryCondition(callable $victoryCondition) {
    $this->victoryCondition = $victoryCondition;
  }
  
  public function getHealers(): callable {
    return $this->healers;
  }
  
  public function setHealers(callable $healers) {
    $this->healers = $healers;
  }
  
  /**
   * Evaluate winner of combat
   * The team which dealt more damage after round limit, wins
   * If all members of one team are eliminated before that, the other team wins
   */
  public function victoryConditionMoreDamage(CombatBase $combat, Team $team1, Team $team2): int {
    $result = 0;
    if($combat->round <= $combat->roundLimit) {
      if(!$team1->hasAliveMembers()) {
        $result = 2;
      } elseif(!$team2->hasAliveMembers()) {
        $result = 1;
      }
    } elseif($combat->round > $combat->roundLimit) {
      $result = ($combat->damage[1] > $combat->damage[2]) ? 1 : 2;
    }
    return $result;
  }
  
  /**
   * Evaluate winner of combat
   * Team 1 wins only if they eliminate all opponents before round limit
   */
  public function victoryConditionEliminateSecondTeam(CombatBase $combat, Team $team1, Team $team2): int {
    $result = 0;
    if($combat->round <= $combat->roundLimit) {
      if(!$team1->hasAliveMembers()) {
        $result = 2;
      } elseif(!$team2->hasAliveMembers()) {
        $result = 1;
      }
    } elseif($combat->round > $combat->roundLimit) {
      $result = (!$combat->team2->hasAliveMembers()) ? 1 : 2;
    }
    return $result;
  }
  
  /**
   * Evaluate winner of combat
   * Team 1 wins if at least 1 of its members is alive after round limit
   */
  public function victoryConditionFirstTeamSurvives(CombatBase $combat, Team $team1, Team $team2): int {
    $result = 0;
    if($combat->round <= $combat->roundLimit) {
      if(!$team1->hasAliveMembers()) {
        $result = 2;
      } elseif(!$team2->hasAliveMembers()) {
        $result = 1;
      }
    } elseif($combat->round > $combat->roundLimit) {
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
  public function getWinner(): int {
    static $result = 0;
    if($result === 0) {
      $result = call_user_func($this->victoryCondition, $this, $this->team1, $this->team2);
      $result = Numbers::range($result, 0, 2);
    }
    return $result;
  }
  
  protected function getTeam(Character $character): Team {
    return $this->team1->hasMember($character->id) ? $this->team1 : $this->team2;
  }
  
  protected function getEnemyTeam(Character $character): Team {
    return $this->team1->hasMember($character->id) ? $this->team2 : $this->team1;
  }
  
  /**
   * Apply pet's effects to character at the start of the combat
   */
  public function deployPets(): void {
    /** @var Character[] $characters */
    $characters = array_merge($this->team1->items, $this->team2->items);
    foreach($characters as $character) {
      if(!is_null($character->activePet)) {
        $effect = $character->getPet($character->activePet)->deployParams;
        $character->addEffect(new CharacterEffect($effect));
      }
    }
  }
  
  /**
   * Apply effects from worn items
   */
  public function equipItems(): void {
    /** @var Character[] $characters */
    $characters = array_merge($this->team1->items, $this->team2->items);
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
   */
  public function setSkillsCooldowns(): void {
    /** @var Character[] $characters */
    $characters = array_merge($this->team1->items, $this->team2->items);
    foreach($characters as $character) {
      foreach($character->skills as $skill) {
        $skill->resetCooldown();
      }
    }
  }
  
  /**
   * Decrease skills' cooldowns
   */
  public function decreaseSkillsCooldowns(): void {
    /** @var Character[] $characters */
    $characters = array_merge($this->team1->items, $this->team2->items);
    foreach($characters as $character) {
      foreach($character->skills as $skill) {
        $skill->decreaseCooldown();
      }
    }
  }
  
  /**
   * Remove combat effects from character at the end of the combat
   */
  public function removeCombatEffects(): void {
    /** @var Character[] $characters */
    $characters = array_merge($this->team1->items, $this->team2->items);
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
   */
  public function logCombatResult(): void {
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
   */
  public function logRoundNumber(): void {
    $this->log->round = ++$this->round;
  }
  
  /**
   * Decrease duration of effects and recalculate stats
   */
  public function recalculateStats(): void {
    /** @var Character[] $characters */
    $characters = array_merge($this->team1->items, $this->team2->items);
    foreach($characters as $character) {
      $character->recalculateStats();
      if($character->hitpoints > 0) {
        $character->calculateInitiative();
      }
    }
  }
  
  /**
   * Reset characters' initiative
   */
  public function resetInitiative(): void {
    /** @var Character[] $characters */
    $characters = array_merge($this->team1->items, $this->team2->items);
    foreach($characters as $character) {
      $character->resetInitiative();
    }
  }
  
  /**
   * Select random character from the team
   */
  protected function selectRandomCharacter(Team $team): ?Character {
    if(count($team->aliveMembers) === 0) {
      return NULL;
    } elseif(count($team) === 1) {
      return $team[0];
    }
    $roll = rand(0, count($team->aliveMembers) - 1);
    return $team->aliveMembers[$roll];
  }
  
  /**
   * Select target for attack
   */
  protected function selectAttackTarget(Character $attacker): ?Character {
    return $this->selectRandomCharacter($this->getEnemyTeam($attacker));
  }
  
  /**
   * Find character with lowest hp in the team
   */
  protected function findLowestHpCharacter(Team $team, int $threshold = NULL): ?Character {
    $lowestHp = 9999;
    $lowestIndex = -1;
    if(is_null($threshold)) {
      $threshold = static::LOWEST_HP_THRESHOLD;
    }
    foreach($team->aliveMembers as $index => $member) {
      if($member->hitpoints <= $member->maxHitpoints * $threshold AND $member->hitpoints < $lowestHp) {
        $lowestHp = $member->hitpoints;
        $lowestIndex = $index;
      }
    }
    if($lowestIndex === -1) {
      return NULL;
    }
    return $team->aliveMembers[$lowestIndex];
  }
  
  /**
   * Select target for healing
   */
  protected function selectHealingTarget(Character $healer): ?Character {
    return $this->findLowestHpCharacter($this->getTeam($healer));
  }
  
  protected function findHealers(): Team {
    $healers = call_user_func($this->healers, $this->team1, $this->team2);
    if($healers instanceof Team) {
      return $healers;
    }
    return new Team("healers");
  }
  
  protected function doSpecialSkill(Character $character1, Character $character2, CharacterSpecialSkillDummy $skill): void {
    switch($skill->skill->target) {
      case SkillSpecial::TARGET_ENEMY:
        $this->onSkillSpecial($character1, $character2, $skill);
        break;
      case SkillSpecial::TARGET_SELF:
        $this->onSkillSpecial($character1, $character1, $skill);
        break;
      case SkillSpecial::TARGET_PARTY:
        $team = $this->getTeam($character1);
        foreach($team as $target) {
          $this->onSkillSpecial($character1, $target, $skill);
        }
        break;
      case SkillSpecial::TARGET_ENEMY_PARTY:
        $team = $this->getEnemyTeam($character1);
        foreach($team as $target) {
          $this->onSkillSpecial($character1, $target, $skill);
        }
        break;
    }
  }
  
  /**
   * Main stage of a round
   */
  public function mainStage(): void {
    /** @var Character[] $characters */
    $characters = array_merge($this->team1->usableMembers, $this->team2->usableMembers);
    usort($characters, function(Character $a, Character $b) {
      return -1 * strcmp((string) $a->initiative, (string) $b->initiative);
    });
    foreach($characters as $character) {
      if($character->hitpoints < 1) {
        continue;
      }
      if(in_array($character, $this->findHealers()->items, true)) {
        $target = $this->selectHealingTarget($character);
        if(!is_null($target)) {
          $this->onHeal($character, $target);
          continue;
        }
      }
      $target = $this->selectAttackTarget($character);
      if(is_null($target)) {
        break;
      }
      if(count($character->usableSkills) > 0) {
        $skill = $character->usableSkills[0];
        if($skill instanceof CharacterAttackSkillDummy) {
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
    return $this->getWinner();
  }
  
  /**
   * Do a round
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
    return $this->getWinner();
  }
  
  /**
   * Executes the combat
   * 
   * @return int Winning team
   */
  public function execute(): int {
    if(!isset($this->team1)) {
      throw new InvalidStateException("Teams are not set.");
    }
    $this->onCombatStart();
    while($this->round <= $this->roundLimit) {
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
   */
  protected function calculateHitChance(Character $character1, Character $character2, CharacterAttackSkillDummy $skill = NULL): int {
    $hitRate = $character1->hit;
    $dodgeRate = $character2->dodge;
    if(!is_null($skill)) {
      $hitRate = $hitRate / 100 * $skill->hitRate;
    }
    return Numbers::range((int) ($hitRate - $dodgeRate), 15, 100);
  }
  
  /**
   * Check whether action succeeded
   */
  protected function hasHit(int $hitChance): bool {
    $roll = rand(0, 100);
    return ($roll <= $hitChance);
  }
  
  /**
   * Do an attack
   * Hit chance = Attacker's hit - Defender's dodge, but at least 15%
   * Damage = Attacker's damage - defender's defense
   */
  public function attackHarm(Character $attacker, Character $defender): void {
    $result = [];
    $hitChance = $this->calculateHitChance($attacker, $defender);
    $result["result"] = $this->hasHit($hitChance);
    $result["amount"] = 0;
    if($result["result"]) {
      $result["amount"] = $attacker->damage - $defender->defense;
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
   */
  public function useAttackSkill(Character $attacker, Character $defender, CharacterAttackSkillDummy $skill): void {
    $result = [];
    $hitChance = $this->calculateHitChance($attacker, $defender, $skill);
    $result["result"] = $this->hasHit($hitChance);
    $result["amount"] = 0;
    if($result["result"]) {
      $result["amount"] = $attacker->damage - $defender->defense;
      $result["amount"] = (int) ($result["amount"] / 100 * $skill->damage);
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
   */
  public function useSpecialSkill(Character $character1, Character $target, CharacterSpecialSkillDummy $skill): void {
    $result = [
      "result" => true, "amount" => 0, "action" => "skill_special", "name" => $skill->skill->name
    ];
    $this->results = $result;
    $effect = [
      "id" => "skill{$skill->skill->id}Effect",
      "type" => $skill->skill->type,
      "stat" => ((in_array($skill->skill->type, SkillSpecial::NO_STAT_TYPES, true)) ? NULL : $skill->skill->stat),
      "value" => $skill->value,
      "source" => "skill",
      "duration" => $skill->skill->duration
    ];
    $target->addEffect(new CharacterEffect($effect));
    $skill->resetCooldown();
  }
  
  /**
   * Calculate success chance of healing
   */
  protected function calculateHealingSuccessChance(Character $healer): int {
    return $healer->intelligence * (int) round($healer->level / 5) + 30;
  }
  
  /**
   * Heal a character
   */
  public function heal(Character $healer, Character $patient): void {
    $result = [];
    $hitChance = $this->calculateHealingSuccessChance($healer);
    $result["result"] = $this->hasHit($hitChance);
    $amount = ($result["result"]) ? $healer->intelligence / 2 : 0;
    if($amount + $patient->hitpoints > $patient->maxHitpoints) {
      $amount = $patient->maxHitpoints - $patient->hitpoints;
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
   * Harm poisoned characters at start of round
   */
  public function applyPoison(): void {
    /** @var Character[] $characters */
    $characters = array_merge($this->team1->aliveMembers, $this->team2->aliveMembers);
    foreach($characters as $character) {
      foreach($character->effects as $effect) {
        if($effect->type === SkillSpecial::TYPE_POISON) {
          $character->harm($effect->value);
          $this->log->log("poison", true, $character, $character, $effect->value);
        }
      }
    }
  }
  
  /**
   * Log results of an action
   */
  public function logResults(Character $character1, Character $character2): void {
    $results = $this->results;
    $this->log->log($results["action"], $results["result"], $character1, $character2, $results["amount"], $results["name"]);
    $this->results = NULL;
  }
  
  /**
   * Log dealt damage
   */
  public function logDamage(Character $attacker, Character $defender): void {
    $team = $this->team1->hasMember($attacker->id) ? 1 : 2;
    $this->damage[$team] += $this->results["amount"];
  }
  
  public function getLog(): CombatLogger {
    return $this->log;
  }
}
?>