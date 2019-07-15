<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterClass;
use HeroesofAbenez\Orm\CharacterRace;
use HeroesofAbenez\Orm\CharacterSpecialization;

/**
 * Character Builder
 *
 * @author Jakub Konečný
 */
final class CharacterBuilder {
  use \Nette\SmartObject;

  /** @var int */
  public const SPECIALIZATION_LEVEL = 15;

  protected function calculateStat(string $stat, CharacterClass $class, CharacterRace $race, int $level): float {
    /** @var int $value */
    $value = $class->$stat + $race->$stat;
    $value += $class->{$stat . "Grow"} * ($level - 1);
    if($level > 1 && $stat === $class->mainStat) {
      $value += ($level - 1) * $class->statPointsLevel;
    }
    return $value;
  }
  
  protected function calculateSpecialistStat(string $stat, CharacterSpecialization $specialization, int $level): float {
    $value = ($specialization->{$stat . "Grow"} * ($level));
    if($stat === $specialization->mainStat) {
      $value += ($level * $specialization->statPointsLevel);
    }
    return $value;
  }

  protected function checkSpecialization(int $level, CharacterClass $class, CharacterSpecialization $specialization = null): void {
    if($level < static::SPECIALIZATION_LEVEL) {
      if(!is_null($specialization)) {
        throw new CannotChooseSpecializationException();
      }
      return;
    } elseif(is_null($specialization)) {
      throw new SpecializationNotChosenException();
    } elseif($specialization->class->id !== $class->id) {
      throw new SpecializationNotAvailableException();
    }
  }

  public function create(CharacterClass $class, CharacterRace $race, int $level = 1, CharacterSpecialization $specialization = null): array {
    $this->checkSpecialization($level, $class, $specialization);
    $specializationLevel = 0;
    if(!is_null($specialization)) {
      $specializationLevel = $level - static::SPECIALIZATION_LEVEL + 1;
      $level = static::SPECIALIZATION_LEVEL - 1;
    }
    $data = [];
    $data["strength"] = $this->calculateStat("strength", $class, $race, $level);
    $data["dexterity"] = $this->calculateStat("dexterity", $class, $race, $level);
    $data["constitution"] = $this->calculateStat("constitution", $class, $race, $level);
    $data["intelligence"] = $this->calculateStat("intelligence", $class, $race, $level);
    $data["charisma"] = $this->calculateStat("charisma", $class, $race, $level);
    if(!is_null($specialization)) {
      $data["strength"] += $this->calculateSpecialistStat("strength", $specialization, $specializationLevel);
      $data["dexterity"] += $this->calculateSpecialistStat("dexterity", $specialization, $specializationLevel);
      $data["constitution"] += $this->calculateSpecialistStat("constitution", $specialization, $specializationLevel);
      $data["intelligence"] += $this->calculateSpecialistStat("intelligence", $specialization, $specializationLevel);
      $data["charisma"] += $this->calculateSpecialistStat("charisma", $specialization, $specializationLevel);
    }
    array_walk($data, function(&$value) {
      $value = (int) $value;
    });
    return $data;
  }
}
?>