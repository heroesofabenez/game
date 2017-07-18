<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

use OutOfBoundsException,
    HeroesofAbenez\Orm\Equipment,
    HeroesofAbenez\Orm\Pet,
    HeroesofAbenez\Orm\BaseCharacterSkill;

/**
 * Structure for single character
 * 
 * @author Jakub Konečný
 * @property-read int|string $id
 * @property-read string $name
 * @property-read string $gender
 * @property-read string $race
 * @property-read string $occupation
 * @property-read int $level
 * @property-read int $experience
 * @property-read int $strength
 * @property-read int $strengthBase
 * @property-read int $dexterity
 * @property-read int $dexterityBase
 * @property-read int $constitution
 * @property-read int $constitutionBase
 * @property-read int $intelligence
 * @property-read int $intelligenceBase
 * @property-read int $charisma
 * @property-read int $charismaBase
 * @property-read int $maxHitpoints
 * @property-read int $hitpoints
 * @property-read int $damage
 * @property-read int $damageBase
 * @property-read int $hit
 * @property-read int $hitBase
 * @property-read int $dodge
 * @property-read int $dodgeBase
 * @property-read int $initiative
 * @property-read int $initiativeBase
 * @property-read string $initiativeFormula
 * @property-read int $defense
 * @property-read int $defenseBase
 * @property-read Equipment[] $equipment
 * @property-read Pet[] $pets
 * @property-read BaseCharacterSkill[] $skills
 * @property-read int|NULL $activePet
 * @property-read CharacterEffect[] $effects
 * @property-read bool $stunned
 * @property-read BaseCharacterSkill[] $usableSkills
 */
class Character {
  use \Nette\SmartObject;
  
  /** @var int|string */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $gender = "male";
  /** @var string */
  protected $race;
  /** @var string */
  protected $occupation;
  /** @var string */
  protected $specialization;
  /** @var int */
  protected $level;
  /** @var int */
  protected $experience = 0;
  /** @var int */
  protected $strength;
  /** @var int */
  protected $strengthBase;
  /** @var int */
  protected $dexterity;
  /** @var int */
  protected $dexterityBase;
  /** @var int */
  protected $constitution;
  /** @var int */
  protected $constitutionBase;
  /** @var int */
  protected $intelligence;
  /** @var int */
  protected $intelligenceBase;
  /** @var int */
  protected $charisma;
  /** @var int */
  protected $charismaBase;
  /** @var int */
  protected $maxHitpoints;
  /** @var int */
  protected $hitpoints;
  /** @var float */
  protected $damage = 0;
  /** @var float */
  protected $damageBase = 0;
  /** @var int */
  protected $hit = 0;
  /** @var int */
  protected $hitBase = 0;
  /** @var int */
  protected $dodge = 0;
  /** @var int */
  protected $dodgeBase = 0;
  /** @var int */
  protected $initiative = 0;
  /** @var int */
  protected $initiativeBase = 0;
  /** @var string */
  protected $initiativeFormula;
  /** @var float */
  protected $defense = 0;
  /** @var float */
  protected $defenseBase = 0;
  /** @var Equipment[] Character's equipment */
  protected $equipment = [];
  /** @var Pet[] Character's pets */
  protected $pets = [];
  /** @var BaseCharacterSkill[] Character's skills */
  protected $skills = [];
  /** @var int|NULL */
  protected $activePet = null;
  /** @var CharacterEffect[] Active effects */
  protected $effects = [];
  /** @var bool */
  protected $stunned = false;
  
  /**
   * 
   * @param array $stats Stats of the character
   * @param Equipment[] $equipment Equipment of the character
   * @param Pet[] $pets Pets owned by the character
   * @param BaseCharacterSkill[] $skills Skills acquired by the character
   */
  function __construct(array $stats, array $equipment = [], array $pets = [], array $skills = []) {
    $this->setStats($stats);
    foreach($equipment as $eq) {
      if($eq instanceof Equipment) {
        $this->equipment[$eq->id] = $eq;
      }
    }
    foreach($pets as $pet) {
      if($pet instanceof Pet) {
        $this->pets[$pet->id] = $pet;
        if($pet->deployed) {
          $this->deployPet($pet->id);
        }
      }
    }
    foreach($skills as $skill) {
      if($skill instanceof BaseCharacterSkill) {
        $this->skills[] = $skill;
      }
    }
  }
  
  protected function setStats(array $stats): void {
    $required_stats = ["id", "name", "occupation", "level", "strength", "dexterity", "constitution", "intelligence", "charisma"];
    $all_stats = array_merge($required_stats, ["race", "specialization", "gender", "experience", "initiativeFormula"]);
    foreach($required_stats as $value) {
      if(!isset($stats[$value])) {
        exit("Not passed all needed elements for parameter stats for method Character::__construct. Missing at least $value.");
      }
    }
    foreach($stats as $key => $value) {
      if(in_array($key, $all_stats)) {
        switch($key) {
          case "name":
            $this->$key = (string) $value;
  break;
          case "strength":
          case "dexterity":
          case "constitution":
          case "intelligence":
          case "charisma":
            if(!is_numeric($value)) {
              exit("Invalid value for \$stats[\"$key\"] passed to method Character::__construct. Expected integer.");
            } else {
              $this->$key = (int) $value;
              $this->{$key . "Base"} = (int) $value;
            }
  break;
          default:
            $this->$key = $value;
  break;
        }
      } else {
        continue;
      }
    }
    $this->hitpoints = $this->maxHitpoints = $this->constitution * 5;
    $this->recalculateSecondaryStats();
    $this->hitBase = $this->hit;
    $this->dodgeBase = $this->dodge;
  }
  
  /**
   * @return int|string
   */
  function getId() {
    return $this->id;
  }
  
  function getName(): string {
    return $this->name;
  }
  
  function getGender(): string {
    return $this->gender;
  }
  
  function getRace(): string {
    return (string) $this->race;
  }
  
  function getOccupation(): string {
    return (string) $this->occupation;
  }
  
  function getLevel(): int {
    return $this->level;
  }
  
  function getExperience(): int {
    return $this->experience;
  }
  
  function getStrength(): int {
    return $this->strength;
  }
  
  function getStrengthBase(): int {
    return $this->strengthBase;
  }
  
  function getDexterity(): int {
    return $this->dexterity;
  }
  
  function getDexterityBase(): int {
    return $this->dexterityBase;
  }
  
  function getConstitution(): int {
    return $this->constitution;
  }
  
  function getConstitutionBase(): int {
    return $this->constitutionBase;
  }
  
  function getCharisma(): int {
    return $this->charisma;
  }
  
  function getCharismaBase(): int {
    return $this->charismaBase;
  }
  
  function getMaxHitpoints(): int {
    return $this->maxHitpoints;
  }
  
  function getHitpoints(): int {
    return $this->hitpoints;
  }
  
  function getDamage(): int {
    return (int) $this->damage;
  }
  
  function getDamageBase(): int {
    return (int) $this->damageBase;
  }
  
  function getHit(): int {
    return $this->hit;
  }
  
  function getHitBase(): int {
    return $this->hitBase;
  }
  
  function getDodge(): int {
    return $this->dodge;
  }
  
  function getDodgeBase(): int {
    return $this->dodgeBase;
  }
  
  function getInitiative(): int {
    return $this->initiative;
  }
  
  function getInitiativeBase(): int {
    return $this->initiativeBase;
  }
  
  function getInitiativeFormula(): string {
    return $this->initiativeFormula;
  }
  
  function getDefense(): int {
    return (int) $this->defense;
  }
  
  function getDefenseBase(): int {
    return (int) $this->defenseBase;
  }
  
  /**
   * @return Equipment[]
   */
  function getEquipment(): array {
    return $this->equipment;
  }
  
  /**
   * @return Pet[]
   */
  function getPets(): array {
    return $this->pets;
  }
  
  /**
   * @return BaseCharacterSkill[]
   */
  function getSkills(): array {
    return $this->skills;
  }
  
  function getActivePet(): ?int {
    return $this->activePet;
  }
  
  /**
   * @return CharacterEffect[]
   */
  function getEffects(): array {
    return $this->effects;
  }
  
  function isStunned(): bool {
    return $this->stunned;
  }
  
  function getSpecialization(): string {
    return $this->specialization;
  }
  
  function getIntelligence(): int {
    return $this->intelligence;
  }
  
  function getIntelligenceBase(): int {
    return $this->intelligenceBase;
  }
  
  /**
   * Applies new effect on the character
   */
  function addEffect(CharacterEffect $effect): void {
    $this->effects[] = $effect;
    $this->recalculateStats();
  }
  
  /**
   * Removes specified effect from the character
   *
   * @throws OutOfBoundsException
   */
  function removeEffect(string $effectId): void {
    foreach($this->effects as $i => $effect) {
      if($effect->id == $effectId) {
        unset($this->effects[$i]);
        $this->recalculateStats();
        return;
      }
    }
    throw new OutOfBoundsException("Effect to remove was not found.");
  }
  
  /**
   * Get specified equipment of the character
   *
   * @throws OutOfBoundsException
   */
  function getItem(int $itemId): Equipment {
    if(isset($this->equipment[$itemId])) {
      return $this->equipment[$itemId];
    } else {
      throw new OutOfBoundsException("Item was not found.");
    }
  }
  
  /**
   * Equips an owned item
   *
   * @throws OutOfBoundsException
   */
  function equipItem(int $itemId): void {
    try {
      $item = $this->getItem($itemId);
    } catch (OutOfBoundsException $e) {
      throw $e;
    }
    $itemBonus = new CharacterEffect($item->deployParams);
    $this->addEffect($itemBonus);
  }
  
  /**
   * Unequips an item
   *
   * @throws OutOfBoundsException
   */
  function unequipItem(int $itemId): void {
    try {
      $item = $this->getItem($itemId);
    } catch (OutOfBoundsException $e) {
      throw $e;
    }
    $itemBonus = $item->deployParams;
    $this->removeEffect($itemBonus["id"]);
  }
  
  /**
   * Get specified pet
   *
   * @throws OutOfBoundsException
   */
  function getPet(int $petId): Pet {
    if(isset($this->pets[$petId]) AND $this->pets[$petId] instanceof Pet) {
      return $this->pets[$petId];
    } else {
      throw new OutOfBoundsException("Pet was not found.");
    }
  }
  
  /**
   * Deploy specified pet (for bonuses)
   *
   * @throws OutOfBoundsException
   */
  function deployPet(int $petId): void {
    try {
      $pet = $this->getPet($petId);
    } catch(OutOfBoundsException $e) {
      throw $e;
    }
    $this->activePet = $petId;
  }
  
  /**
   * Dismisses active pet
   */
  function dismissPet(): void {
    $this->activePet = NULL;
  }
  
  /**
   * @return BaseCharacterSkill[]
   */
  function getUsableSkills(): array {
    $skills = [];
    foreach($this->skills as $skill) {
      if($skill->canUse()) {
        $skills[] = $skill;
      }
    }
    return $skills;
  }
  
  /**
   * Harm the character
   */
  function harm(int $amount): void {
    $this->hitpoints -= $amount;
  }
  
  /**
   * Heal the character
   */
  function heal(int $amount): void {
    $this->hitpoints += $amount;
  }
  
  /**
   * Determine which (primary) stat should be used to calculate damage
   */
  function damageStat(): string {
    $stat = "strength";
    foreach($this->equipment as $item) {
      if(!$item->worn OR $item->slot != "weapon") {
        continue;
      }
      switch($item->type) {
        case "staff":
          $stat = "intelligence";
          break;
        case "club":
          $stat = "constitution";
          break;
        case "bow":
        case "throwing knife":
          $stat = "dexterity";
          break;
      }
    }
    return $stat;
  }
  
  /**
   * Recalculate secondary stats from the the primary ones
   */
  function recalculateSecondaryStats(): void {
    $stats = ["damage" => $this->damageStat(), "hit" => "dexterity", "dodge" => "dexterity"];
    foreach($stats as $secondary => $primary) {
      $gain = $this->$secondary - $this->{$secondary . "Base"};
      if($secondary === "damage") {
        $base = round($this->$primary / 2) + 1;
      } else {
        $base = $this->$primary * 3;
      }
      $this->$secondary = $base + $gain;
    }
  }
  
  /**
   * Recalculates stats of the character (mostly used during combat)
   */
  function recalculateStats(): void {
    $stats = [
      "strength", "dexterity", "constitution", "intelligence", "charisma",
      "damage", "hit", "dodge", "initiative", "defense"
    ];
    $stunned = false;
    $debuffs = [];
    foreach($stats as $stat) {
      $$stat = $this->{$stat . "Base"};
      $debuffs[$stat] = 0;
    }
    foreach($this->effects as $i => $effect) {
      $stat = $effect->stat;
      $type = $effect->type;
      $duration = $effect->duration;
      if(is_int($duration) AND $duration < 0) {
        unset($this->effects[$i]);
        continue;
      }
      switch($effect->source) {
        case "pet":
        case "skill":
          if($type != "stun") {
            $bonus_value = $$stat / 100 * $effect->value;
          }
  break;
        case "equipment":
          if($type != "stun") {
            $bonus_value = $effect->value;
          }
  break;
      }
      if($type == "buff") {
        $$stat += $bonus_value;
      } elseif($type == "debuff") {
        $debuffs[$stat] += $bonus_value;
      } elseif($type == "stun") {
        $stunned = true;
      }
      unset($stat, $type, $duration, $bonus_value);
    }
    foreach($debuffs as $stat => $value) {
      $value = min($value, 80);
      $bonus_value = $$stat / 100 * $value;
      $$stat -= $bonus_value;
    }
    foreach($stats as $stat) {
      $this->$stat = (int) round($$stat);
    }
    $this->recalculateSecondaryStats();
    $this->stunned = $stunned;
  }
  
  /**
   * Calculate character's initiative
   */
  function calculateInitiative(): void {
    $result = 0;
    $formula = str_replace(["INT", "DEX"], [$this->intelligence, $this->dexterity], $this->initiativeFormula);
    preg_match_all("/^([1-9]+)d([1-9]+)/", $formula, $dices);
    for($i = 1; $i <= (int) $dices[1][0]; $i++) {
      $result += rand(1, (int) $dices[2][0]);
    }
    preg_match_all("/\+([0-9]+)\/([0-9]+)/", $formula, $ammendum);
    $result += (int) $ammendum[1][0] / (int) $ammendum[2][0];
    $this->initiative = (int) $result;
  }
  
  /**
   * Reset character's initiative
   */
  function resetInitiative(): void {
    $this->initiative = $this->initiativeBase;
  }
}
?>