<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nexendrie\Utils\Numbers;

/**
 * PveArenaOpponent
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property CharacterRace $race {m:1 CharacterRace::$arenaNpcs}
 * @property string $gender {enum self::GENDER_*} {default self::GENDER_MALE}
 * @property CharacterClass $occupation {m:1 CharacterClass::$arenaNpcs}
 * @property int $level {default 1}
 * @property int $strength
 * @property int $dexterity
 * @property int $constitution
 * @property int $intelligence
 * @property int $charisma
 * @property Equipment|null $weapon {m:1 Equipment::$arenaNpcsWeapon}
 * @property Equipment|null $armor {m:1 Equipment::$arenaNpcsArmor}
 */
final class PveArenaOpponent extends \Nextras\Orm\Entity\Entity {
  public const GENDER_MALE = "male";
  public const GENDER_FEMALE = "female";
  
  protected function setterLevel(int $value): int {
    return Numbers::range($value, 1, 999);
  }
}
?>