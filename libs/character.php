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
      if ($pet instanceof Pet) {
        $this->pets[] = $pet;
      }
    }
    $key = $value = $pet = "";
  }
  
  function addEffect($effect = array()) {
    $neededParams = array ("id", "type", "stat", "value", "source", "duration");
    $types = array("buff");
    $stats = array("strength", "dexterity", "constitution", "intelligence", "charisma");
    $sources = array("pet", "skill");
    foreach($neededParams as $param) {
      if(!isset($effect[$param])) exit("Not passed all needed elements for parameter effect for method Character::addEffect.");
    }
    if(!is_int($effect["value"])) exit("Invalid value for \$effect[\"value\"] passed to method Character::addEffect. Expected integer.");
    if(!in_array($effect["sources"]), $sources) exit("Invalid value for \$effect[\"sources\"] passed to method Character::addEffect.");
    if(!in_array($effect["stat"]), $stats) exit("Invalid value for \$effect[\"stat\"] passed to method Character::addEffect.");
    if(!in_array($effect["type"]), $types) exit("Invalid value for \$effect[\"type\"] passed to method Character::addEffect.");
    $this->effects[] = $effect;
  }
  
  function removeEffect($effectId) {
    for ($ = 0; $i <= $coun($this->effects); $i++) {
    	if($this->effects[$i]["id"] == $effectId) {
        unset($this->effects[$i]);
        return true;
      }
    }
    return false;
  }
  
  function equipItem($item) {  }
  
  function unequipItem($itemId) {  }
  
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
      $petBonus = $pet->deployParams();
      $this->addEffect($petBonus);
    }
  }
  
  function dismissPet() {
    if(is_int($this->active_pet)) {
      $petBonusEffectId = "pet" . $this->active_pet . "bonusEffect";
      $this->removeEffect($petBonusEffectId);
      $this->active_pet = null;
    }
  }
  
  function recalculateStats($in_combat = false) { }
}
?>