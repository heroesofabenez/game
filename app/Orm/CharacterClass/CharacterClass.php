<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * CharacterClass
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $description
 * @property int $strength
 * @property float $strengthGrow
 * @property int $dexterity
 * @property float $dexterityGrow
 * @property int $constitution
 * @property float $constitutionGrow
 * @property int $intelligence
 * @property float $intelligenceGrow
 * @property int $charisma
 * @property float $charismaGrow
 * @property float $statPointsLevel
 * @property string $initiative
 * @property OneHasMany|CharacterSpecialization[] $specializations {1:m CharacterSpecialization::$class}
 * @property OneHasMany|PetType[] $petTypes {1:m PetType::$requiredClass}
 * @property OneHasMany|QuestArea[] $areas {1:m QuestArea::$requiredOccupation}
 * @property OneHasMany|QuestStage[] $stages {1:m QuestStage::$requiredOccupation}
 * @property OneHasMany|Character[] $characters {1:m Character::$occupation}
 */
class CharacterClass extends \Nextras\Orm\Entity\Entity {
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