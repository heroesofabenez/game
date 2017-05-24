<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

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
 * @property int|NULL $weapon
 * @todo add relationship from $weapon to Equipment
 */
class PveArenaOpponent extends \Nextras\Orm\Entity\Entity {
  const GENDER_MALE = "male";
  const GENDER_FEMALE = "female";
  
  /**
   * @param int $value
   * @return int
   */
  protected function setterLevel(int $value): int {
    if($value < 1) {
      return 1;
    } elseif($value > 999) {
      return 999;
    } else {
      return $value;
    }
  }
}
?>