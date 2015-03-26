<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
class Character extends Object {
  private $id;
  private $name;
  private $gender;
  private $occupation;
  private $specialization;
  private $level;
  private $experience;
  private $strength;
  private $base_strength;
  private $dexterity;
  private $base_dexterity;
  private $constitution;
  private $base_constitution;
  private $intelligence;
  private $base_intelligence;
  private $charisma;
  private $base_charisma;
  private $max_hitpoints;
  private $hitpoints;
  private $damage;
  private $hit;
  private $dodge;
  private $initiative;
  private $description;
  private $guild = null;
  private $guild_rank = null;
  private $equipment = array();
  private $pets = array();
  private $active_pet = null;
  private $effects = array();
  function __construct($stats = array(), $equipment = array(), $pets = array()) {
    if(!is_array($stats)) { exit("Invalid value for parameter stats passed to method Character::__construct. Expected array."); }
    if(!is_array($equipment)) { exit("Invalid value for parameter equipment passed to method Character::__construct. Expected array."); }
    if(!is_array($pets)) { exit("Invalid value for parameter pets passed to method Character::__construct. Expected array."); }
    $required_stats = array("id", "name", "gender", "occupation", "level", "experience", "strength", "dexterity", "constitution", "intelligence");
    $all_stats = $required_stats + array("specialization", "description", "guild", "guild_rank");
    foreach($required_stats as $value) {
      if(!isset($stats[$value])) exit("Not passed all needed elements for parameter stats for method Character::__construct.");
    }
    foreach($stats as $key => $value) {
      if(in_array($key, $all_stats)) {
switch($key) {
case "name":
case "description":
  if(!is_string($value)) { exit("Invalid value for \$stats[\"$key\"] passed to method Character::__construct. Expected string."); }
  else ( $this->$key = $value; )
  break;
case "strength":
case "dexterity":
case "constitution":
case "constitution":
case "charisma":
  if(!is_int($value)) {
    exit("Invalid value for \$stats[\"$key\"] passed to method Character::__construct. Expected integer.");
  } else {
    $this->$key = $value;
    $this->base_$key = $value;
  } 
  break;
}
      } else { continue; }
    }
    foreach($pets as $pet) {
      if($pet instanceof Pet) {
        $this->pets[] = $pet;
      }
    }
    $key = $value = $pet = "";
  }
  
  function addEffect($effect = array()) {
    $neededParams = array ("id", "type", "stat", "value", "source", "duration");
    $types = array("buff", "debuff");
    $stats = array("strength", "dexterity", "constitution", "intelligence", "charisma");
    $sources = array("pet", "skill", "equipment");
    $durations = array("combat", "forever");
    foreach($neededParams as $param) {
      if(!isset($effect[$param])) exit("Not passed all needed elements for parameter effect for method Character::addEffect.");
    }
    if(!is_int($effect["value"])) exit("Invalid value for \$effect[\"value\"] passed to method Character::addEffect. Expected integer.");
    if(!in_array($effect["sources"]), $sources) exit("Invalid value for \$effect[\"sources\"] passed to method Character::addEffect.");
    if(!in_array($effect["stat"], $stats)) exit("Invalid value for \$effect[\"stat\"] passed to method Character::addEffect.");
    if(!in_array($effect["type"], $types)) exit("Invalid value for \$effect[\"type\"] passed to method Character::addEffect.");
    if(!in_array($effect["duration"], $durations) or $effect["duration"] < 0) exit("Invalid value for \$effect[\"duration\"] passed to method Character::addEffect.");
    $this->effects[] = $effect;
    $this->recalculateStats();
  }
  
  function removeEffect($effectId) {
    for($ = 0; $i <= count($this->effects); $i++) {
    	if($this->effects[$i]["id"] == $effectId) {
        unset($this->effects[$i]);
        $this->recalculateStats();
        return true;
      }
    }
    return false;
  }
  
  function getItem($itemid) {
    if(isset($this->equipment[$itemid])) { return $this->equipment[$itemid]; }
    else { return false; }
  }
  
  function equipItem($itemId) {
    $item = $this->getItem($itemId)
    if(!$item) {
      exit;
    } else {
      $itemBonus = $item->deployParams();
      $this->addEffect($itemBonus);
    }
  }
  
  function unequipItem($itemId) {
  $item = $this->getItem($itemId)
    if(!$item) {
      exit;
    } else {
      $itemBonus = $item->deployParams();
      $this->removeEffect($itemBonus->id);
    }
  }
  
  function getPet($petId) {
    if(isset($this->pets[$petId]) and is_a($this->pets[$petId], "Pet")) { return $this->pets[$petId]; }
    else { return false; }
  }
  
  function deployPet($petId) {
    $pet = $this->getPet($petId)
    if(!$pet) {
      exit;
    } else {
      $this->active_pet = $petId;
    }
  }
  
  function dismissPet() {
    if(is_int($this->active_pet)) {
      $this->active_pet = null;
    }
  }
  
  function recalculateStats() {
    $strength = $this->base_strength;
    $dexterity = $this->base_dexterity;
    $constitution = $this->base_constitution;
    $intellingence = $this->base_intelligence;
    $charisma = $this->base_charisma;
    $debuffs = array();
    $i = 0;
    foreach($this->effects as $effect) {
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
  $bonus_value = $$stat / 100 * $value;
	break;
case "equipment":
  $bonus_value = $effect->value;
  break;
}
      if($type == "buff") { $$stat += $bonus_value; }
      elseif($type == "debuff") { $debuffs[$stat] += $value; }
      $stat = $type = $duration = $bonus_value = "";
      $i++;
    }
    foreach($debuffs as $stat => $value) {
      if($value > 80) $value = 80;
      $bonus_value = $$stat / 100 * $value;
      $$stat -= $bonus_value;
    }
    $this->strength = $strength;
    $this->dexterity = $dexterity;
    $this->constitution = $constitution;
    $this->intellingence = $intelligence;
    $this->charisma = $charisma;
  }
}
?>