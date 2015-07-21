<?php
namespace HeroesofAbenez\Entities;

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
  protected $gender;
  /** @var string */
  protected $occupation;
  /** @var string */
  protected $specialization;
  /** @var int */
  protected $level;
  /** @var int */
  protected $experience;
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
  /** @var string */
  protected $description;
  /** @var int */
  protected $guild = null;
  /** @var string Position in guild */
  protected $guild_rank = null;
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
    $required_stats = array("id", "name", "gender", "occupation", "level", "experience", "strength", "dexterity", "constitution", "intelligence");
    $all_stats = $required_stats + array("specialization", "guild", "guild_rank");
    foreach($required_stats as $value) {
      if(!isset($stats[$value])) exit("Not passed all needed elements for parameter stats for method Character::__construct. Missing at least $value.");
    }
    foreach($stats as $key => $value) {
      if(in_array($key, $all_stats)) {
        switch($key) {
case "name":
  if(!is_string($value)) exit("Invalid value for \$stats[\"$key\"] passed to method Character::__construct. Expected string."); else $this->$key = $value;
  break;
case "strength":
case "dexterity":
case "constitution":
case "constitution":
case "intelligence":
case "charisma":
case "damage":
case "hit":
case "dodge":
case "initiative":
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
  
  function addEffect(CharacterEffect $effect) {
    $this->effects[] = $effect;
    $this->recalculateStats();
  }
  
  /**
   * Removes specified effect from the character
   * 
   * @param int $effectId Effect to remove
   * @return bool Success
   */
  function removeEffect($effectId) {
    for($i = 0; $i <= count($this->effects); $i++) {
      if($this->effects[$i]->id == $effectId) {
        unset($this->effects[$i]);
        $this->recalculateStats();
        return true;
      }
    }
    return false;
  }
  
  /**
   * Get specified equipment of the character
   * 
   * @param int $itemid Item's id
   * @return Equipment Item if found else false
   */
  function getItem($itemid) {
    if(isset($this->equipment[$itemid])) return $this->equipment[$itemid];
    else return false;
  }
  
  function equipItem($itemId) {
    $item = $this->getItem($itemId);
    if(!$item) {
      exit;
    } else {
      $itemBonus = $item->deployParams();
      $this->addEffect($itemBonus);
    }
  }
  
  function unequipItem($itemId) {
    $item = $this->getItem($itemId);
    if(!$item) {
      exit;
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
   */
  function getPet($petId) {
    if(isset($this->pets[$petId]) AND $this->pets[$petId] instanceof Pet) return $this->pets[$petId];
    else return false;
  }
  
  /**
   * Deploy specified pet (for bonuses)
   * 
   * @param int $petId Pet's id
   * @return void
   */
  function deployPet($petId) {
    $pet = $this->getPet($petId);
    if(!$pet) exit("Cannot find pet with id $petId.");
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
   * Recalculates stats of the character (mostly used during combat)
   * 
   * @return void
   */
  function recalculateStats() {
    $stats = array(
      "strength", "dexterity", "constitution", "intelligence", "charisma",
      "damage", "hit", "dodge", "initiative"
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
    $this->stunned = $stunned;
  }
}
?>