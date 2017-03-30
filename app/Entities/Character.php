<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

use OutOfBoundsException;

/**
 * Structure for single character
 * 
 * @author Jakub Konečný
 * @property-read CharacterSkill[] $usableSkills
 */
class Character extends BaseEntity {
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
  protected $base_strength;
  /** @var int */
  protected $dexterity;
  /** @var int */
  protected $base_dexterity;
  /** @var int */
  protected $constitution;
  /** @var int */
  protected $base_constitution;
  /** @var int */
  protected $intelligence;
  /** @var int */
  protected $base_intelligence;
  /** @var int */
  protected $charisma;
  /** @var int */
  protected $base_charisma;
  /** @var int */
  protected $max_hitpoints;
  /** @var int */
  protected $hitpoints;
  /** @var int */
  protected $damage = 0;
  /** @var int */
  protected $base_damage = 0;
  /** @var int */
  protected $hit = 0;
  /** @var int */
  protected $base_hit = 0;
  /** @var int */
  protected $dodge = 0;
  /** @var int */
  protected $base_dodge = 0;
  /** @var int */
  protected $initiative = 0;
  /** @var int */
  protected $base_initiative = 0;
  /** @var string */
  protected $initiative_formula;
  /** @var int */
  protected $defense = 0;
  /** @var int */
  protected $base_defense = 0;
  /** @var Equipment[] Character's equipment */
  protected $equipment = [];
  /** @var Pet[] Character's pets */
  protected $pets = [];
  /** @var CharacterSkillAttack[] Character's skills */
  protected $skills = [];
  /** @var int|NULL */
  protected $active_pet = null;
  /** @var CharacterEffect[] Active effects */
  protected $effects = [];
  /** @var bool */
  protected $stunned = false;
  
  /**
   * 
   * @param array $stats Stats of the character
   * @param Equipment[] $equipment Equipment of the character
   * @param Pet[] $pets Pets owned by the character
   * @param CharacterSkill[] $skills Skills acquired by the character
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
      if($skill instanceof CharacterSkill) {
        $this->skills[] = $skill;
      }
    }
  }
  
  /**
   * @param array $stats
   * @return void
   */
  protected function setStats(array $stats): void {
    $required_stats = ["id", "name", "occupation", "level", "strength", "dexterity", "constitution", "intelligence", "charisma"];
    $all_stats = array_merge($required_stats, ["race", "specialization", "gender", "experience", "initiative_formula"]);
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
              $this->{"base_" . $key} = (int) $value;
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
    $this->hitpoints = $this->max_hitpoints = $this->constitution * 5;
    $this->recalculateSecondaryStats();
    $this->base_hit = $this->hit;
    $this->base_dodge = $this->dodge;
  }
  
  /**
   * Applies new effect on the character
   * 
   * @param CharacterEffect $effect
   * @return void
   */
  function addEffect(CharacterEffect $effect): void {
    $this->effects[] = $effect;
    $this->recalculateStats();
  }
  
  /**
   * Removes specified effect from the character
   * 
   * @param string $effectId Effect to remove
   * @return void
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
   * @param int $itemId Item's id
   * @return Equipment Item
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
   * @param int $itemId
   * @return void
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
   * @param int $itemId
   * @return void
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
   * @param int $petId Pet's id
   * @return Pet
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
   * @param int $petId Pet's id
   * @return void
   * @throws OutOfBoundsException
   */
  function deployPet(int $petId): void {
    try {
      $pet = $this->getPet($petId);
    } catch(OutOfBoundsException $e) {
      throw $e;
    }
    $this->active_pet = $petId;
  }
  
  /**
   * Dismisses active pet
   * 
   * @return void
   */
  function dismissPet(): void {
    $this->active_pet = NULL;
  }
  
  /**
   * @return CharacterSkill[]
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
   * 
   * @param int $amount Number of hitpoints to lose
   * @return void
   */
  function harm(int $amount): void {
    $this->hitpoints -= $amount;
  }
  
  /**
   * Heal the character
   * 
   * @param int $amount Number of hitpoints to gain
   * @return void
   */
  function heal(int $amount): void {
    $this->hitpoints += $amount;
  }
  
  /**
   * Determine which (primary) stat should be used to calculate damage
   * 
   * @return string
   */
  function damageStat(): string {
    $stat = "strength";
    foreach($this->equipment as $item) {
      if(!$item->worn OR $item->slot != "weapon") continue;
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
   * 
   * @return void
   */
  function recalculateSecondaryStats(): void {
    $stats = ["damage" => $this->damageStat(), "hit" => "dexterity", "dodge" => "dexterity"];
    foreach($stats as $secondary => $primary) {
      $gain = $this->$secondary - $this->{"base_$secondary"};
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
   * 
   * @return void
   */
  function recalculateStats(): void {
    $stats = [
      "strength", "dexterity", "constitution", "intelligence", "charisma",
      "damage", "hit", "dodge", "initiative", "defense"
    ];
    $stunned = false;
    $debuffs = [];
    foreach($stats as $stat) {
      $$stat = $this->{"base_" . $stat};
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
      if($value > 80) $value = 80;
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
   *
   * @return void
   * @todo maybe use a regular expression
   */
  function calculateInitiative(): void {
    $result = 0;
    $formula = str_replace(["INT", "DEX"], [$this->intelligence, $this->dexterity], $this->initiative_formula);
    $pos = strpos($formula, "d");
    $dices = [(int) substr($formula, 0, $pos), (int) substr($formula, $pos +1, strpos($formula, "+") - $pos - 1)];
    for($i = 1; $i <= $dices[0]; $i++) {
      $result += rand(1, $dices[1]);
    }
    $this->initiative = $result;
  }
  
  /**
   * Reset character's initiative
   *
   * @return void
   */
  function resetInitiative(): void {
    $this->initiative = $this->base_initiative;
  }
}
?>