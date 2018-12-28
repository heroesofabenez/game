<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterClass;
use HeroesofAbenez\Orm\CharacterRace;

/**
 * Character Builder
 *
 * @author Jakub Konečný
 */
final class CharacterBuilder {
  use \Nette\SmartObject;

  /** @var int */
  public const SPECIALIZATION_LEVEL = 15;

  protected function calculateStat(string $stat, CharacterClass $class, CharacterRace $race, int $level): int {
    /** @var int $value */
    $value = $class->$stat + $race->$stat;
    $value += (int) ($class->{$stat . "Grow"} * ($level - 1));
    if($level > 1 AND $stat === $class->mainStat) {
      $value += (int) (($level - 1) * $class->statPointsLevel);
    }
    return $value;
  }

  public function create(CharacterClass $class, CharacterRace $race, int $level = 1): array {
    $data = [];
    $data["strength"] = $this->calculateStat("strength", $class, $race, $level);
    $data["dexterity"] = $this->calculateStat("dexterity", $class, $race, $level);
    $data["constitution"] = $this->calculateStat("constitution", $class, $race, $level);
    $data["intelligence"] = $this->calculateStat("intelligence", $class, $race, $level);
    $data["charisma"] = $this->calculateStat("charisma", $class, $race, $level);
    return $data;
  }
}
?>