<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;
use Nexendrie\Utils\Numbers;

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
 * @property CharacterClass|null $requiredClass {m:1 CharacterClass::$petTypes}
 * @property CharacterRace|null $requiredRace {m:1 CharacterRace::$petTypes}
 * @property int $cost {default 0}
 * @property OneHasMany|Pet[] $pets {1:m Pet::$type}
 */
final class PetType extends \Nextras\Orm\Entity\Entity {
  public const STAT_STR = "str";
  public const STAT_DEX = "dex";
  public const STAT_CON = "con";
  public const STAT_INT = "int";
  
  protected function setterBonusValue(int $value): int {
    return Numbers::range($value, 0, 99);
  }
  
  protected function setterRequiredLevel(int $value): int {
    return Numbers::range($value, 0, 99);
  }
}
?>