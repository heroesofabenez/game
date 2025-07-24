<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterRace;
use HeroesofAbenez\Orm\CharacterClass;
use HeroesofAbenez\Orm\Model as ORM;
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

  private \Nette\Security\User $user;
  /** @var string[] */
  private array $stats = ["strength", "dexterity", "constitution", "intelligence", "charisma"];
  
  public function __construct(private readonly ORM $orm) {
  }
  
  protected function setUser(\Nette\Security\User $user): void {
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
    if($char === null) {
      return null;
    }
    $stats = [
      "id", "name", "gender", "level", "race", "strength", "dexterity", "constitution", "intelligence", "charisma",
      "class", "specialization", "predominantKarma",
    ];
    foreach($stats as $stat) {
      if(is_scalar($char->$stat) || $char->$stat === null) {
        $return[$stat] = $char->$stat;
      } else {
        $return[$stat] = $char->$stat->id;
      }
    }
    $return["guild"] = "";
    if($char->guild !== null) {
      $return["guild"] = $char->guild->id;
      $return["guildrank"] = ($char->guildrank !== null) ? $char->guildrank->id : null;
    }
    $return["stage"] = $return["area"] = null;
    if($char->currentStage !== null) {
      $return["stage"] = $char->currentStage->name;
      $return["area"] = $char->currentStage->area->name;
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
    if($character->level + 1 < CharacterBuilder::SPECIALIZATION_LEVEL || $character->specialization !== null) {
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
      if($specialization !== null) {
        throw new CannotChooseSpecializationException();
      }
      return;
    } elseif($character->specialization !== null && $specialization !== null) {
      throw new SpecializationAlreadyChosenException();
    } elseif($character->specialization === null && $specialization === null) {
      throw new SpecializationNotChosenException();
    } elseif($specialization !== null && !in_array($specialization, $this->getAvailableSpecializations(), true)) {
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
    if($specialization !== null) {
      $character->specialization = $specialization;
    }
    if($character->specialization !== null) {
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
    $character->lastActive = new \DateTimeImmutable();
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

  public function getCharismaBonus(): int {
    /** @var Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    return $character->charismaBonus;
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
    $character->lastActive = new \DateTimeImmutable();
    $this->orm->characters->persistAndFlush($character);
  }
}
?>