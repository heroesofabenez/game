<?php
namespace HeroesofAbenez;

/**
 * Structure for single character
 * 
 * @author Jakub Konečný
 */
class Character extends \Nette\Object {
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
  
  /**
   * 
   * @param array $stats Stats of the character
   * @param array $equipment Equipment of the character
   * @param array $pets Pets owned by the character
   */
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
  if(!is_string($value)) exit("Invalid value for \$stats[\"$key\"] passed to method Character::__construct. Expected string."); else $this->$key = $value;
  break;
case "strength":
case "dexterity":
case "constitution":
case "constitution":
case "charisma":
case "damage":
case "hit":
case "dodge":
case "initiative":
  if(!is_int($value)) {
    exit("Invalid value for \$stats[\"$key\"] passed to method Character::__construct. Expected integer.");
  } else {
    $this->$key = $value;
    $key = "base_$key";
    $this->$key = $value;
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
    if(!in_array($effect["sources"], $sources)) exit("Invalid value for \$effect[\"sources\"] passed to method Character::addEffect.");
    if(!in_array($effect["stat"], $stats)) exit("Invalid value for \$effect[\"stat\"] passed to method Character::addEffect.");
    if(!in_array($effect["type"], $types)) exit("Invalid value for \$effect[\"type\"] passed to method Character::addEffect.");
    if(!in_array($effect["duration"], $durations) or $effect["duration"] < 0) exit("Invalid value for \$effect[\"duration\"] passed to method Character::addEffect.");
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
    	if($this->effects[$i]["id"] == $effectId) {
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
    if(isset($this->equipment[$itemid])) { return $this->equipment[$itemid]; }
    else { return false; }
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
   * @return Pet Pet if found else false
   */
  function getPet($petId) {
    if(isset($this->pets[$petId]) and is_a($this->pets[$petId], "Pet")) { return $this->pets[$petId]; }
    else { return false; }
  }
  
  /**
   * Deploy specified pet (for bonuses)
   * 
   * @param int $petId Pet's id
   * @return void
   */
  function deployPet($petId) {
    $pet = $this->getPet($petId);
    if(!$pet) {
      exit;
    } else {
      $this->active_pet = $petId;
    }
  }
  
  /**
   * Dismisses active pet
   * 
   * @return void
   */
  function dismissPet() {
    if(is_int($this->active_pet)) {
      $this->active_pet = null;
    }
  }
  
  /**
   * Recalculates stats of the character (mostly used during combat)
   * 
   * @return void
   */
  function recalculateStats() {
    $strength = $this->base_strength;
    $dexterity = $this->base_dexterity;
    $constitution = $this->base_constitution;
    $intelligence = $this->base_intelligence;
    $charisma = $this->base_charisma;
    $damage = $this->base_damage;
    $hit = $this->base_hit;
    $dodge = $this->base_dodge;
    $initiative = $this->base_initiative;
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
  $bonus_value = $$stat / 100 * $effect->value;
	break;
case "equipment":
  $bonus_value = $effect->value;
  break;
}
      if($type == "buff") { $$stat += $bonus_value; }
      elseif($type == "debuff") { $debuffs[$stat] += $bonus_value; }
      unset($stat, $type, $duration, $bonus_value);
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
    $this->damage = $damage;
    $this->hit = $hit;
    $this->dodge = $dodge;
    $this->initiative = $initiative;
  }
}

/**
 * Model Character
 * 
 * @author Jakub Konečný
 */
class CharacterModel extends \Nette\Object {
  /**
   * Get list of races
   * 
   * @param \Nette\Di\Container $container
   * @return array
   */
  static function getRacesList(\Nette\Di\Container $container) {
    $cache = $container->getService("caches.characters");
    $racesList = $cache->load("races");
    if($racesList === NULL) {
      $racesList = array();
      $db = $container->getService("database.default.context");
      $races = $db->table("character_races");
      foreach($races as $race) {
        $racesList[$race->id] = $race->name;
      }
      $cache->save("races", $racesList);
    }
    return $racesList;
  }
  
  /**
   * Get list of classes
   * 
   * @param \Nette\Di\Container $container
   * @return array
   */
  static function getClassesList(\Nette\Di\Container $container) {
    $cache = $container->getService("caches.characters");
    $classesList = $cache->load("classes");
    if($classesList === NULL) {
      $classesList = array();
      $db = $container->getService("database.default.context");
      $classes = $db->table("character_classess");
      foreach($classes as $class) {
        $classesList[$class->id] = $class->name;
      }
      $cache->save("classes", $classesList);
    }
    return $classesList;
  }
  
  /**
   * Creates new character
   * 
   * @param type $values
   * @param \Nette\Database\Context $db
   */
  static function create($values, \Nette\Database\Context $db) {
    $data = array(
      "name" => $values["name"], "race" => $values["race"],
      "occupation" => $values["class"], "gender" => $values["gender"]
    );
    $chars = $db->table("characters")->where("name", $data["name"]);
    if($chars->count("*") > 0) return false;
    
    $race = $db->table("character_races")->get($values["race"]);
    $class = $db->table("character_classess")->get($values["class"]);
    $data["strength"] = $class->strength + $race->strength;
    $data["dexterity"] = $class->dexterity + $race->dexterity;
    $data["constitution"] = $class->constitution + $race->constitution;
    $data["intelligence"] = $class->intelligence + $race->intelligence;
    $data["charisma"] = $class->charisma + $race->charisma;
    $data["owner"] = Presenters\BasePresenter::getRealId();
    $db->query("INSERT INTO characters", $data);
    
    $data["class"] = $class->name;
    $data["race"] = $race->name;
    if($data["gender"]  == 1) $data["gender"] = "male";
    else $data["gender"] = "female";
    unset($data["occupation"]);
    return $data;
  }
}
?>