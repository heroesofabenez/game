<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterRace;
use HeroesofAbenez\Orm\CharacterClass;
use HeroesofAbenez\Orm\Model as ORM;
use HeroesofAbenez\Orm\CharacterSpecialization;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Collection\ICollection;

  /**
   * Model Profile
   * 
   * @author Jakub Konečný
   * @property-write \Nette\Security\User $user
   */
final class Profile {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \HeroesofAbenez\Model\Pet */
  protected $petModel;
  /** @var \Nette\Security\User */
  protected $user;
  /** @var string[] */
  private $stats = ["strength", "dexterity", "constitution", "intelligence", "charisma"];
  
  public function __construct(ORM $orm, Pet $petModel) {
    $this->orm = $orm;
    $this->petModel = $petModel;
  }
  
  public function setUser(\Nette\Security\User $user): void {
    $this->user = $user;
  }
  
  /**
   * Get list of races
   * 
   * @return ICollection|CharacterRace[]
   */
  public function getRacesList(): ICollection {
    return $this->orm->races->findAll();
  }
  
  /**
   * Get data about specified race
   */
  public function getRace(int $id): ?CharacterRace {
    return $this->orm->races->getById($id);
  }
  
  /**
   * Get name of specified race
   */
  public function getRaceName(int $id): string {
    $race = $this->getRace($id);
    if(is_null($race)) {
      return "";
    }
    return $race->name;
  }
  
  /**
   * Get list of classes
   * 
   * @return ICollection|CharacterClass[]
   */
  public function getClassesList(): ICollection {
    return $this->orm->classes->findAll();
  }
  
  /**
   * Get data about specified class
   */
  public function getClass(int $id): ?CharacterClass {
    return $this->orm->classes->getById($id);
  }
  
  /**
   * Get name of specified class
   */
  public function getClassName(int $id): string {
    $class = $this->getClass($id);
    if(is_null($class)) {
      return "";
    }
    return $class->name;
  }
  
  /**
   * Get data about specified specialization
   */
  public function getSpecialization(int $id): ?CharacterSpecialization {
    return $this->orm->specializations->getById($id);
  }
  
  /**
   * Get name of specified specialization
   */
  public function getSpecializationName(int $id): string {
    $specialization = $this->getSpecialization($id);
    if(is_null($specialization)) {
      return "";
    }
    return $specialization->name;
  }
  
  /**
   * Get character's id
   */
  public function getCharacterId(string $name): int {
    $character = $this->orm->characters->getByName($name);
    if(is_null($character)) {
      return 0;
    }
    return $character->id;
  }
  
  /**
   * Get character's name
   */
  public function getCharacterName(int $id): string {
    $character = $this->orm->characters->getById($id);
    if(is_null($character)) {
      return "";
    }
    return $character->name;
  }
  
  /**
   * Get character's guild
   */
  public function getCharacterGuild(int $id): int {
    $char = $this->orm->characters->getById($id);
    if(is_null($char)) {
      return 0;
    } elseif(is_null($char->guild)) {
      return 0;
    }
    return $char->guild->id;
  }
  
  /**
   * Gets basic data about specified player
   */
  public function view(int $id): ?array {
    $return = [];
    $char = $this->orm->characters->getById($id);
    if(is_null($char)) {
      return null;
    }
    $stats = [
      "id", "name", "gender", "level", "race", "description", "strength", "dexterity",
      "constitution", "intelligence", "charisma", "occupation", "specialization", "predominantKarma",
    ];
    foreach($stats as $stat) {
      if($char->$stat instanceof IEntity) {
        $return[$stat] = $char->$stat->id;
      } else {
        $return[$stat] = $char->$stat;
      }
    }
    $return["guild"] = "";
    if(!is_null($char->guild)) {
      $return["guild"] = $char->guild->id;
      $return["guildrank"] = (!is_null($char->guildrank)) ? $char->guildrank->id : null;
    }
    $return["pet"] = $this->petModel->getActivePet($id);
    return $return;
  }
  
  /**
   * Get needed experiences for level up
   * 
   * @return int[]
   */
  public function getLevelsRequirements(): array {
    $xps = [2 => 65];
    for($i = 3; $i <= 100; $i++) {
      $xps[$i] = (int) ($xps[$i - 1] * 1.35) + 15;
    }
    return $xps;
  }
  
  /**
   * Level up your character
   *
   * @throws NotEnoughExperiencesException
   */
  public function levelUp(): void {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    if($character->experience < $this->getLevelsRequirements()[$character->level + 1]) {
      throw new NotEnoughExperiencesException();
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
   */
  public function getStatPoints(): int {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    return (int) $character->statPoints;
  }
  
  /**
   * Get user's stats
   * 
   * @return int[]
   */
  public function getStats(): array {
    $stats = ["strength", "dexterity", "constitution", "intelligence", "charisma",];
    $return = [];
    $char = $this->orm->characters->getById($this->user->id);
    foreach($stats as $stat) {
      $return[$stat] = $char->$stat;
    }
    return $return;
  }
  
  /**
   * Improve a stat
   *
   * @throws InvalidStatException
   * @throws NoStatPointsAvailableException
   */
  public function trainStat(string $stat): void {
    if(!in_array($stat, $this->stats, true)) {
      throw new InvalidStatException();
    } elseif($this->getStatPoints() < 1) {
      throw new NoStatPointsAvailableException();
    }
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $character->{$stat}++;
    $character->statPoints--;
    $this->orm->characters->persistAndFlush($character);
  }
}
?>