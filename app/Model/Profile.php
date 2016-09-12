<?php
namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Entities\CharacterRace,
    HeroesofAbenez\Entities\CharacterClass,
    HeroesofAbenez\Entities\CharacterSpecialization;

  /**
   * Model Profile
   * 
   * @author Jakub Konečný
 * @property-write \Nette\Security\User $user
   */
class Profile {
  use \Nette\SmartObject;
  
  /** @var \Nette\Database\Context  */
  protected $db;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \HeroesofAbenez\Model\Pet */
  protected $petModel;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var string[] */
  private $stats;
  
  function __construct(\Nette\Database\Context $db, \Nette\Caching\Cache $cache, Pet $petModel) {
    $this->db = $db;
    $this->cache = $cache;
    $this->petModel = $petModel;
    $this->stats = ["strength", "dexterity", "constitution", "intelligence", "charisma"];
  }
  
  function setUser(\Nette\Security\User $user) {
    $this->user = $user;
  }
  
  /**
   * Get list of races
   * 
   * @return CharacterRace[]
   */
  function getRacesList() {
    $racesList = $this->cache->load("races");
    if($racesList === NULL) {
      $racesList = [];
      $races = $this->db->table("character_races");
      foreach($races as $race) {
        $racesList[$race->id] = new CharacterRace($race);
      }
      $this->cache->save("races", $racesList);
    }
    return $racesList;
  }
  
  /**
   * Get data about specified race
   * 
   * @param int $id Race's id
   * @return CharacterRace|bool
   */
  function getRace($id) {
    $races = $this->getRacesList();
    $race = Arrays::get($races, $id, false);
    return $race;
  }
  
  /**
   * Get name of specified race
   * 
   * @param int $id Race's id
   * @return string
   */
  function getRaceName($id) {
    $race = $this->getRace($id);
    if(!$race) return "";
    else return $race->name;
  }
  
  /**
   * Get list of classes
   * 
   * @return CharacterClass[]
   */
  function getClassesList() {
    $classesList = $this->cache->load("classes");
    if($classesList === NULL) {
      $classesList = [];
      $classes = $this->db->table("character_classess");
      foreach($classes as $class) {
        $classesList[$class->id] = new CharacterClass($class);
      }
      $this->cache->save("classes", $classesList);
    }
    return $classesList;
  }
  
  /**
   * Get data about specified class
   * 
   * @param int $id Class' id
   * @return CharacterClass|bool
   */
  function getClass($id) {
    $classes = $this->getClassesList();
    $class = Arrays::get($classes, $id, false);
    return $class;
  }
  
  /**
   * Get name of specified class
   * 
   * @param int $id
   * @return string
   */
  function getClassName($id) {
    $class = $this->getClass($id);
    if(!$class) return "";
    else return $class->name;
  }
  
  /**
   * Get list of specializations
   * 
   * @return CharacterSpecialization[]
   */
  function getSpecializationsList() {
    $specializationsList = $this->cache->load("specializations");
    if($specializationsList === NULL) {
      $specializationsList = [];
      $specializations = $this->db->table("character_specializations");
      foreach($specializations as $specialization) {
        $specializationsList[$specialization->id] = new CharacterSpecialization($specialization);
      }
      $this->cache->save("specializations", $specializationsList);
    }
    return $specializationsList;
  }
  
  /**
   * Get data about specified specialization
   * 
   * @param int $id Specialization's id
   * @return CharacterSpecialization|bool
   */
  function getSpecialization($id) {
    $races = $this->getSpecializationsList();
    $race = Arrays::get($races, $id, false);
    return $race;
  }
  
  /**
   * Get name of specified specialization
   * 
   * @param int $id Specialization's id
   * @return string
   */
  function getSpecializationName($id) {
    $race = $this->getSpecialization($id);
    if(!$race) return "";
    else return $race->name;
  }
  
  /**
   * @return \stdClass[]
   */
  function getCharacters() {
    $return = [];
    $stats = ["id", "name"];
    $characters = $this->cache->load("characters");
    if($characters === NULL) {
      $characters = $this->db->table("characters");
      foreach($characters as $character) {
        $char = new \stdClass;
        foreach($stats as $stat) {
          $char->$stat = $character->$stat;
        }
        $return[$character->id] = $char;
      }
      $this->cache->save("characters", $return);
    } else {
      $return = $characters;
    }
    return $return;
  }
  
  /**
   * Get character's id
   * 
   * @param string $name Character's name
   * @return int
   */
  function getCharacterId($name) {
    $characters = $this->getCharacters();
    foreach($characters as $char) {
      if($char->name == $name) return $char->id;
    }
    return 0;
  }
  
  /**
   * Get character's name
   * 
   * @param int $id Character's id
   * @return string
   */
  function getCharacterName($id) {
    $characters = $this->getCharacters();
    return $characters[$id]->name;
  }
  
  /**
   * Get character's guild
   * 
   * @param string $id Character's id
   * @return int
   */
  function getCharacterGuild($id) {
    $char = $this->db->table("characters")->get($id);
    if(!$char) return 0;
    return $char->guild;
  }
  
  /**
   * Gets basic data about specified player
   * 
   * @param integer $id character's id
   * @return array info about character
   */
  function view($id) {
    $return = [];
    $char = $this->db->table("characters")->get($id);
    if(!$char) { return false; }
    $stats = [
      "id", "name", "gender", "level", "race", "description", "strength", "dexterity",
      "constitution", "intelligence", "charisma", "occupation", "specialization"
    ];
    foreach($stats as $stat) {
      $return[$stat] = $char->$stat;
    }
    if($char->guild > 0) {
      $return["guild"] = $char->guild;
      $return["guildrank"] = $char->guildrank;
    } else {
      $return["guild"] = "";
    }
    $return["pet"] = $this->petModel->getActivePet($id);
    return $return;
  }
  
  /**
   * Get needed experiences for level up
   * 
   * @return int[]
   */
  function getLevelsRequirements() {
    $xps = [2 => 65];
    for($i = 3; $i <= 100; $i++) {
      $xps[$i] = (int) ($xps[$i-1] * 1.35) + 15;
    }
    return $xps;
  }
  
  /**
   * Level up your character
   * 
   * @return void
   * @throws NotEnoughExperiencesException
   */
  function levelUp() {
    $character = $this->db->table("characters")->get($this->user->id);
    if($character->experience < $this->getLevelsRequirements()[$character->level + 1]) throw new NotEnoughExperiencesException;
    if($character->specialization) $class = $this->getSpecialization($character->specialization);
    else $class = $this->getClass($character->occupation);
    $data = "level=level+1, stat_points=stat_points+$class->stat_points_level, skill_points=skill_points+1";
    foreach($this->stats as $stat) {
      $grow = $class->{$stat . "_grow"};
      if($grow > 0) $data .= ", $stat=$stat+$grow";
    }
    $where = ["id" => $this->user->id];
    $this->db->query("UPDATE characters SET $data WHERE ?", $where);
  }
  
  /**
   * Get amount of user's usable stat points
   * 
   * @return int
   */
  function getStatPoints() {
    return (int) $this->db->table("characters")->get($this->user->id)->stat_points;
  }
  
  /**
   * Get user's stats
   * 
   * @return int[]
   */
  function getStats() {
    $char = $this->db->table("characters")->get($this->user->id);
    $return = [];
    foreach($this->stats as $stat) {
      $return[$stat] = $char->$stat;
    }
    return $return;
  }
  
  /**
   * Improve a stat
   * 
   * @param string $stat
   * @return void
   * @throws InvalidStatException
   * @throws NoStatPointsAvailableException
   */
  function trainStat($stat) {
    if(!in_array($stat, $this->stats)) throw new InvalidStatException;
    elseif($this->getStatPoints() < 1) throw new NoStatPointsAvailableException;
    $data = "$stat=$stat+1, stat_points=stat_points-1";
    $where = ["id" => $this->user->id];
    $this->db->query("UPDATE characters SET $data WHERE ?", $where);
  }
}
?>