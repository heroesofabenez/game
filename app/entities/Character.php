<?php
namespace HeroesofAbenez\Entities;

use OutOfBoundsException;

/**
 * Structure for single character
 * 
 * @author Jakub Konečný
 */
class Character extends BaseEntity {
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $gender = "male";
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
  protected $damage;
  /** @var int */
  protected $base_damage;
  /** @var int */
  protected $hit;
  /** @var int */
  protected $base_hit;
  /** @var int */
  protected $dodge;
  /** @var int */
  protected $base_dodge;
  /** @var int */
  protected $initiative;
  /** @var int */
  protected $base_initiative;
  /** @var int */
  protected $defense;
  /** @var int */
  protected $base_defense;
  /** @var array Character's equipment */
  protected $equipment = array();
  /** @var array Character's pets */
  protected $pets = array();
  /** @var int */
  protected $active_pet = null;
  protected $effects = array();
  /** @var bool */
  protected $stunned = false;
  
  /**
   * 
   * @param array $stats Stats of the character
   * @param array $equipment Equipment of the character
   * @param array $pets Pets owned by the character
   */
  function __construct(array $stats, array $equipment = array(), array $pets = array()) {
    $this->setStats($stats);
    foreach($equipment as $eq) {
      if($eq instanceof Equipment)
        $this->equipment[$eq->id] = $eq;
    }
    foreach($pets as $pet) {
      if($pet instanceof Pet) {
        $this->pets[$pet->id] = $pet;
      }
    }
  }
  
  /**
   * @param array $stats
   * @return void
   */
  protected function setStats(array $stats) {
    $required_stats = array("id", "name", "occupation", "level", "strength", "dexterity", "constitution", "intelligence", "charisma");
    $all_stats = $required_stats + array("specialization", "gender", "experience");
    foreach($required_stats as $value) {
      if(!isset($stats[$value])) exit("Not passed all needed elements for parameter stats for method Character::__construct. Missing at least $value.");
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
case "constitution":
case "intelligence":
case "charisma":
  if(!is_int($value)) {
    exit("Invalid value for \$stats[\"$key\"] passed to method Character::__construct. Expected integer.");
  } else {
    $this->$key = $value;
    $this->{"base_" . $key} = $value;
  }
  break;
default:
  $this->$key = $value;
  break;
        }
      } else { continue; }
    }
    $this->hitpoints = $this->max_hitpoints = $this->constitution * 5;
    $this->damage = $this->base_damage = round($this->strength / 2) + 1;
    $this->dodge = $this->base_dodge = $this->dexterity * 3;
    $this->hit = $this->base_hit = $this->dexterity * 3;
    $this->defense = $this->base_defense = 0;
  }
  
  /**
   * Applies new effect on the character
   * 
   * @param \HeroesofAbenez\Entities\CharacterEffect $effect
   * @return void
   */
  function addEffect(CharacterEffect $effect) {
    $this->effects[] = $effect;
    $this->recalculateStats();
  }
  
  /**
   * Removes specified effect from the character
   * 
   * @param int $effectId Effect to remove
   * @return void
   * @throws \OutOfBoundsException
   */
  function removeEffect($effectId) {
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
   * @param int $itemid Item's id
   * @return Equipment Item
   * @throws \OutOfBoundsException
   */
  function getItem($itemid) {
    if(isset($this->equipment[$itemid])) return $this->equipment[$itemid];
    else throw new OutOfBoundsException("Item was not found.");
  }
  
  /**
   * Equips an owned item
   * 
   * @param int $itemId
   * @return void
   * @throws \OutOfBoundsException
   */
  function equipItem($itemId) {
    $item = $this->getItem($itemId);
    if(!$item) {
      throw new OutOfBoundsException("Item to equip was not found.");
    } else {
      $itemBonus = new CharacterEffect($item->deployParams);
      $this->addEffect($itemBonus);
    }
  }
  
  /**
   * Ubequips an item
   * 
   * @param int $itemId
   * @return void
   * @throws \OutOfBoundsException
   */
  function unequipItem($itemId) {
    $item = $this->getItem($itemId);
    if(!$item) {
      throw new OutOfBoundsException("Item to unequip was not found.");
    } else {
      $itemBonus = $item->deployParams();
      $this->removeEffect($itemBonus->id);
    }
  }
  
  /**
   * Get specified pet
   * 
   * @param int $petId Pet's id
   * @return \HeroesofAbenez\Entities\Pet
   * @throws \OutOfBoundsException
   */
  function getPet($petId) {
    if(isset($this->pets[$petId]) AND $this->pets[$petId] instanceof Pet) return $this->pets[$petId];
    else throw new OutOfBoundsException("Pet was not found.");
  }
  
  /**
   * Deploy specified pet (for bonuses)
   * 
   * @param int $petId Pet's id
   * @return void
   * @throws \OutOfBoundsException
   */
  function deployPet($petId) {
    $pet = $this->getPet($petId);
    if(!$pet) throw new OutOfBoundsException("Pet to deploy was not found.");
    else $this->active_pet = $petId;
  }
  
  /**
   * Dismisses active pet
   * 
   * @return void
   */
  function dismissPet() {
    if(is_int($this->active_pet)) $this->active_pet = null;
  }
  
  /**
   * Harm the character
   * 
   * @param int $amount Number of hitpoints to lose
   * @return void
   */
  function harm($amount) {
    $this->hitpoints -= $amount;
  }
  
  /**
   * Heal the character
   * 
   * @param int $amount Number of hitpoints to gain
   * @return void
   */
  function heal($amount) {
    $this->hitpoints += $amount;
  }
  
  /**
   * Recalculate secondary stats from the the primary ones
   * 
   * @return void
   */
  function recalculateSecondaryStats() {
    $stats = array("damage" => "strength", "hit" => "dexterity", "dodge" => "dexterity");
    foreach($stats as $secondary => $primary) {
      $gain = $this->$secondary - $this->{"base_$secondary"};
      if($primary === "strength") $base = round($this->$primary / 2) + 1;
      else $base = $this->$primary * 3;
      $this->$secondary = $base + $gain;
    }
  }
  
  /**
   * Recalculates stats of the character (mostly used during combat)
   * 
   * @return void
   */
  function recalculateStats() {
    $stats = array(
      "strength", "dexterity", "constitution", "intelligence", "charisma",
      "damage", "hit", "dodge", "initiative", "defense"
    );
    $stunned = false;
    foreach($stats as $stat) {
      $$stat = $this->{"base_" . $stat};
    }
    $debuffs = array();
    foreach($this->effects as $i => $effect) {
      $stat = $effect->stat;
      $type = $effect->type;
      $duration = $effect->duration;
      if(is_int($duration) and $duration < 0) {
        unset($this->effects[$i]);
        continue;
      }
      switch($effect->source) {
case "pet":
case "skill":
  if($type != "stun") $bonus_value = $$stat / 100 * $effect->value;
  break;
case "equipment":
  if($type != "stun") $bonus_value = $effect->value;
  break;
      }
      if($type == "buff") { $$stat += $bonus_value; }
      elseif($type == "debuff") { $debuffs[$stat] += $bonus_value; }
      elseif($type == "stun") { $stunned = true; }
      unset($stat, $type, $duration, $bonus_value);
    }
    foreach($debuffs as $stat => $value) {
      if($value > 80) $value = 80;
      $bonus_value = $$stat / 100 * $value;
      $$stat -= $bonus_value;
    }
    foreach($stats as $stat) {
      $this->$stat = round($$stat);
    }
    $this->recalculateSecondaryStats();
    $this->stunned = $stunned;
  }
}
?>