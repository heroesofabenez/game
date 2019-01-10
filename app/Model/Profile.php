<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterRace;
use HeroesofAbenez\Orm\CharacterClass;
use HeroesofAbenez\Orm\Model as ORM;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Collection\ICollection;
use HeroesofAbenez\Orm\Character;

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
  /** @var \Nette\Security\User */
  protected $user;
  /** @var string[] */
  private $stats = ["strength", "dexterity", "constitution", "intelligence", "charisma"];
  
  public function __construct(ORM $orm) {
    $this->orm = $orm;
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
    return $this->orm->races->findBy(["playable" => true]);
  }
  
  /**
   * Get list of classes
   * 
   * @return ICollection|CharacterClass[]
   */
  public function getClassesList(): ICollection {
    return $this->orm->classes->findBy(["playable" => true]);
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
      "id", "name", "gender", "level", "race", "strength", "dexterity", "constitution", "intelligence", "charisma",
      "class", "specialization", "predominantKarma",
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
    $return["stage"] = $return["area"] = null;
    if(!is_null($char->currentStage)) {
      $return["stage"] = $char->currentStage->id;
      $return["area"] = $char->currentStage->area->id;
    }
    $return["pet"] = $char->activePet;
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
   * Get available specializations
   * Returns nothing if level is too level or specialization was already chosen
   *
   * @return int[]
   */
  public function getAvailableSpecializations(): array {
    /** @var Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    if($character->level + 1 < CharacterBuilder::SPECIALIZATION_LEVEL OR !is_null($character->specialization)) {
      return [];
    }
    return $this->orm->specializations->findByClass($character->class)
      ->fetchPairs(null, "id");
  }

  /**
   * Check specialization choice on level up
   *
   * @throws CannotChooseSpecializationException
   * @throws SpecializationAlreadyChosenException
   * @throws SpecializationNotChosenException
   * @throws SpecializationNotAvailableException
   */
  protected function checkSpecializationChoice(Character $character, int $specialization = null): void {
    if($character->level + 1 < CharacterBuilder::SPECIALIZATION_LEVEL) {
      if(!is_null($specialization)) {
        throw new CannotChooseSpecializationException();
      }
      return;
    } elseif(!is_null($character->specialization) AND !is_null($specialization)) {
      throw new SpecializationAlreadyChosenException();
    } elseif(is_null($character->specialization) AND is_null($specialization)) {
      throw new SpecializationNotChosenException();
    } elseif(!is_null($specialization) AND !in_array($specialization, $this->getAvailableSpecializations(), true)) {
      throw new SpecializationNotAvailableException();
    }
  }
  
  /**
   * Level up your character
   *
   * @throws NotEnoughExperiencesException
   * @throws CannotChooseSpecializationException
   * @throws SpecializationAlreadyChosenException
   * @throws SpecializationNotChosenException
   * @throws SpecializationNotAvailableException
   */
  public function levelUp(int $specialization = null): void {
    /** @var Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    if($character->experience < $this->getLevelsRequirements()[$character->level + 1]) {
      throw new NotEnoughExperiencesException();
    }
    $this->checkSpecializationChoice($character, $specialization);
    if(!is_null($specialization)) {
      $character->specialization = $specialization;
    }
    if(!is_null($character->specialization)) {
      $class = $character->specialization;
    } else {
      $class = $character->class;
    }
    $character->level++;
    $character->statPoints += $class->statPointsLevel;
    $character->skillPoints++;
    foreach($this->stats as $stat) {
      $grow = $class->{$stat . "Grow"};
      $character->$stat += $grow;
    }
    $this->orm->characters->persistAndFlush($character);
  }

  /**
   * Get amount of user's usable stat points
   */
  public function getStatPoints(): int {
    /** @var Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    return (int) $character->statPoints;
  }

  /**
   * Get user's stats
   * 
   * @return int[]
   */
  public function getStats(): array {
    $return = [];
    $char = $this->orm->characters->getById($this->user->id);
    foreach($this->stats as $stat) {
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
    }
    /** @var Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    if($character->statPoints < 1) {
      throw new NoStatPointsAvailableException();
    }
    $character->{$stat}++;
    $character->statPoints--;
    $this->orm->characters->persistAndFlush($character);
  }
}
?>