<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * PetType
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $bonusStat {enum self::STAT_*}
 * @property int $bonusValue
 * @property string $image
 * @property int $requiredLevel
 * @property CharacterClass|NULL $requiredClass {m:1 CharacterClass::$petTypes}
 * @property CharacterRace|NULL $requiredRace {m:1 CharacterRace::$petTypes}
 * @property int $cost {default 0}
 * @property OneHasMany|Pet[] $pets {1:m Pet::$type}
 */
class PetType extends \Nextras\Orm\Entity\Entity {
  const STAT_STR = "str";
  const STAT_DEX = "dex";
  const STAT_CON = "con";
  const STAT_INT = "int";
  
  /**
   * @param int $value
   * @return int
   */
  protected function setterBonusValue(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > 99) {
      return 99;
    } else {
      return $value;
    }
  }
  
  /**
   * @param int $value
   * @return int
   */
  protected function setterRequiredLevel(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > 99) {
      return 99;
    } else {
      return $value;
    }
  }
}
?>