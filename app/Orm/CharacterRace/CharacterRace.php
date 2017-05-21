<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterRace
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string|NULL $description
 * @property int $strength {default 0}
 * @property int $dexterity {default 0}
 * @property int $constitution {default 0}
 * @property int $intelligence {default 0}
 * @property int $charisma {default 0}
 */
class CharacterRace extends \Nextras\Orm\Entity\Entity {
  const MAX_STATS = 99;
  
  /**
   * @param int $value
   * @return int
   */
  protected function setterStrength(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > static::MAX_STATS) {
      return static::MAX_STATS;
    } else {
      return $value;
    }
  }
  
  /**
   * @param int $value
   * @return int
   */
  protected function setterDexterity(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > static::MAX_STATS) {
      return static::MAX_STATS;
    } else {
      return $value;
    }
  }
  
  /**
   * @param int $value
   * @return int
   */
  protected function setterConstitution(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > static::MAX_STATS) {
      return static::MAX_STATS;
    } else {
      return $value;
    }
  }
  
  /**
   * @param int $value
   * @return int
   */
  protected function setterIntelligence(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > static::MAX_STATS) {
      return static::MAX_STATS;
    } else {
      return $value;
    }
  }
  
  /**
   * @param int $value
   * @return int
   */
  protected function setterCharisma(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > static::MAX_STATS) {
      return static::MAX_STATS;
    } else {
      return $value;
    }
  }
}
?>