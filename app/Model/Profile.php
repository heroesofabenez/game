<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterRace,
    HeroesofAbenez\Orm\CharacterClass,
    HeroesofAbenez\Orm\Model as ORM,
    HeroesofAbenez\Orm\CharacterSpecialization,
    Nextras\Orm\Entity\IEntity,
    Nextras\Orm\Collection\ICollection;

  /**
   * Model Profile
   * 
   * @author Jakub Konečný
 * @property-write \Nette\Security\User $user
   */
class Profile {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \HeroesofAbenez\Model\Pet */
  protected $petModel;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var string[] */
  private $stats;
  
  function __construct(ORM $orm, Pet $petModel) {
    $this->orm = $orm;
    $this->petModel = $petModel;
    $this->stats = ["strength", "dexterity", "constitution", "intelligence", "charisma"];
  }
  
  function setUser(\Nette\Security\User $user) {
    $this->user = $user;
  }
  
  /**
   * Get list of races
   * 
   * @return ICollection|CharacterRace[]
   */
  function getRacesList(): ICollection {
    return $this->orm->races->findAll();
  }
  
  /**
   * Get data about specified race
   * 
   * @param int $id Race's id
   * @return CharacterRace|NULL
   */
  function getRace(int $id): ?CharacterRace {
    return $this->orm->races->getById($id);
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
   * @return ICollection|CharacterClass[]
   */
  function getClassesList(): ICollection {
    return $this->orm->classes->findAll();
  }
  
  /**
   * Get data about specified class
   * 
   * @param int $id Class' id
   * @return CharacterClass|NULL
   */
  function getClass(int $id): ?CharacterClass {
    return $this->orm->classes->getById($id);
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
   * Get data about specified specialization
   * 
   * @param int $id Specialization's id
   * @return CharacterSpecialization|NULL
   */
  function getSpecialization(int $id): ?CharacterSpecialization {
    return $this->orm->specializations->getById($id);
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
   * Get character's id
   * 
   * @param string $name Character's name
   * @return int
   */
  function getCharacterId(string $name): int {
    $character = $this->orm->characters->getByName($name);
    if(is_null($character)) {
      return 0;
    }else {
      return $character->id;
    }
  }
  
  /**
   * Get character's name
   * 
   * @param int $id Character's id
   * @return string
   */
  function getCharacterName(int $id): string {
    $character = $this->orm->characters->getById($id);
    if(is_null($character)) {
      return "";
    } else {
      return $character->name;
    }
  }
  
  /**
   * Get character's guild
   * 
   * @param int $id Character's id
   * @return int
   */
  function getCharacterGuild(int $id): int {
    $char = $this->orm->characters->getById($id);
    if(is_null($char)) {
      return 0;
    } else {
      return $char->guild->id;
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
    $char = $this->orm->characters->getById($id);
    if(is_null($char)) {
      return NULL;
    }
    $stats = [
      "id", "name", "gender", "level", "race", "description", "strength", "dexterity",
      "constitution", "intelligence", "charisma", "occupation", "specialization",
    ];
    foreach($stats as $stat) {
      if($char->$stat instanceof IEntity) {
        $return[$stat] = $char->$stat->id;
      } else {
        $return[$stat] = $char->$stat;
      }
    }
    if($char->guild->id > 0) {
      $return["guild"] = $char->guild->id;
      $return["guildrank"] = ($char->guildrank) ? $char->guildrank->id : NULL;
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
    $character = $this->orm->characters->getById($this->user->id);
    if($character->experience < $this->getLevelsRequirements()[$character->level + 1]) {
      throw new NotEnoughExperiencesException;
    }
    if(!is_null($character->specialization)) {
      $class = $character->specialization;
    } else {
      $class = $character->occupation;
    }
    $character->level++;
    $character->statPoints += $class->statPointsLevel;
    $character->skillPoints++;
    foreach($this->stats as $stat) {
      $grow = $class->{$stat . "Grow"};
      if($grow > 0) {
        $character->$stat += $grow;
      }
    }
    $this->orm->characters->persistAndFlush($character);
  }
  
  /**
   * Get amount of user's usable stat points
   * 
   * @return int
   */
  function getStatPoints(): int {
    return (int) $this->orm->characters->getById($this->user->id)->statPoints;
  }
  
  /**
   * Get user's stats
   * 
   * @return int[]
   */
  function getStats(): array {
    $char = $this->orm->characters->getById($this->user->id);
    return $char->toArray(IEntity::TO_ARRAY_RELATIONSHIP_AS_ID);
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
    $character = $this->orm->characters->getById($this->user->id);
    $character->{$stat}++;
    $character->statPoints--;
    $this->orm->characters->persistAndFlush($character);
  }
}
?>