<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use Nette\Utils\Arrays,
    HeroesofAbenez\Orm\CharacterRace,
    HeroesofAbenez\Orm\CharacterClass,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\CharacterRaceDummy,
    HeroesofAbenez\Orm\CharacterClassDummy,
    HeroesofAbenez\Orm\CharacterSpecializationDummy;

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
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Caching\Cache */
  protected $cache;
  /** @var \HeroesofAbenez\Model\Pet */
  protected $petModel;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var string[] */
  private $stats;
  
  function __construct(ORM $orm, \Nette\Database\Context $db, \Nette\Caching\Cache $cache, Pet $petModel) {
    $this->db = $db;
    $this->orm = $orm;
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
   * @return CharacterRaceDummy[]
   */
  function getRacesList(): array {
    $racesList = $this->cache->load("races", function(& $dependencies) {
      $racesList = [];
      $races = $this->orm->races->findAll();
      /** @var CharacterRace $race */
      foreach($races as $race) {
        $racesList[$race->id] = new CharacterRaceDummy($race);
      }
      return $racesList;
    });
    return $racesList;
  }
  
  /**
   * Get data about specified race
   * 
   * @param int $id Race's id
   * @return CharacterRaceDummy|NULL
   */
  function getRace(int $id): ?CharacterRaceDummy {
    $races = $this->getRacesList();
    return Arrays::get($races, $id, NULL);
  }
  
  /**
   * Get name of specified race
   * 
   * @param int $id Race's id
   * @return string
   */
  function getRaceName(int $id): string {
    $race = $this->getRace($id);
    if(is_null($race)) {
      return "";
    } else {
      return $race->name;
    }
  }
  
  /**
   * Get list of classes
   * 
   * @return CharacterClassDummy[]
   */
  function getClassesList(): array {
    $classesList = $this->cache->load("classes", function(& $dependencies) {
      $classesList = [];
      $classes = $this->orm->classes->findAll();
      /** @var CharacterClass $class */
      foreach($classes as $class) {
        $classesList[$class->id] = new CharacterClassDummy($class);
      }
      return $classesList;
    });
    return $classesList;
  }
  
  /**
   * Get data about specified class
   * 
   * @param int $id Class' id
   * @return CharacterClassDummy|NULL
   */
  function getClass(int $id): ?CharacterClassDummy {
    $classes = $this->getClassesList();
    return Arrays::get($classes, $id, NULL);
  }
  
  /**
   * Get name of specified class
   * 
   * @param int $id
   * @return string
   */
  function getClassName(int $id): string {
    $class = $this->getClass($id);
    if(is_null($class)) {
      return "";
    } else {
      return $class->name;
    }
  }
  
  /**
   * Get list of specializations
   * 
   * @return CharacterSpecializationDummy[]
   */
  function getSpecializationsList(): array {
    $specializationsList = $this->cache->load("specializations", function(& $dependencies) {
      $specializationsList = [];
      $specializations = $this->orm->specializations->findAll();
      foreach($specializations as $specialization) {
        $specializationsList[$specialization->id] = new CharacterSpecializationDummy($specialization);
      }
      return $specializationsList;
    });
    return $specializationsList;
  }
  
  /**
   * Get data about specified specialization
   * 
   * @param int $id Specialization's id
   * @return CharacterSpecializationDummy|NULL
   */
  function getSpecialization(int $id): ?CharacterSpecializationDummy {
    $specializations = $this->getSpecializationsList();
    return Arrays::get($specializations, $id, NULL);
  }
  
  /**
   * Get name of specified specialization
   * 
   * @param int $id Specialization's id
   * @return string
   */
  function getSpecializationName(int $id): string {
    $specialization = $this->getSpecialization($id);
    if(is_null($specialization)) {
      return "";
    } else {
      return $specialization->name;
    }
  }
  
  /**
   * @return \stdClass[]
   */
  function getCharacters(): array {
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
  function getCharacterId(string $name): int {
    $characters = $this->getCharacters();
    foreach($characters as $char) {
      if($char->name == $name) {
        return $char->id;
      }
    }
    return 0;
  }
  
  /**
   * Get character's name
   * 
   * @param int $id Character's id
   * @return string
   */
  function getCharacterName(int $id): string {
    $characters = $this->getCharacters();
    return $characters[$id]->name;
  }
  
  /**
   * Get character's guild
   * 
   * @param int $id Character's id
   * @return int
   */
  function getCharacterGuild(int $id): int {
    $char = $this->db->table("characters")->get($id);
    if(!$char) {
      return 0;
    } else {
      return $char->guild;
    }
  }
  
  /**
   * Gets basic data about specified player
   * 
   * @param integer $id character's id
   * @return array|NULL info about character
   */
  function view(int $id): ?array {
    $return = [];
    $char = $this->db->table("characters")->get($id);
    if(!$char) {
      return NULL;
    }
    $stats = [
      "id", "name", "gender", "level", "race", "description", "strength", "dexterity",
      "constitution", "intelligence", "charisma", "occupation", "specialization",
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
  function getLevelsRequirements(): array {
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
  function levelUp(): void {
    $character = $this->db->table("characters")->get($this->user->id);
    if($character->experience < $this->getLevelsRequirements()[$character->level + 1]) {
      throw new NotEnoughExperiencesException;
    }
    if($character->specialization) {
      $class = $this->getSpecialization($character->specialization);
    } else {
      $class = $this->getClass($character->occupation);
    }
    $data = "level=level+1, stat_points=stat_points+$class->stat_points_level, skill_points=skill_points+1";
    foreach($this->stats as $stat) {
      $grow = $class->{$stat . "_grow"};
      if($grow > 0) {
        $data .= ", $stat=$stat+$grow";
      }
    }
    $where = ["id" => $this->user->id];
    $this->db->query("UPDATE characters SET $data WHERE ?", $where);
  }
  
  /**
   * Get amount of user's usable stat points
   * 
   * @return int
   */
  function getStatPoints(): int {
    return (int) $this->db->table("characters")->get($this->user->id)->stat_points;
  }
  
  /**
   * Get user's stats
   * 
   * @return int[]
   */
  function getStats(): array {
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
  function trainStat(string $stat): void {
    if(!in_array($stat, $this->stats)) {
      throw new InvalidStatException;
    } elseif($this->getStatPoints() < 1) {
      throw new NoStatPointsAvailableException;
    }
    $data = "$stat=$stat+1, stat_points=stat_points-1";
    $where = ["id" => $this->user->id];
    $this->db->query("UPDATE characters SET $data WHERE ?", $where);
  }
}
?>