<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * SkillAttack
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $description
 * @property CharacterClass $neededClass {m:1 CharacterClass::$attackSkills}
 * @property CharacterSpecialization|NULL $neededSpecialization {m:1 CharacterSpecialization::$attackSkills}
 * @property int $neededLevel
 * @property string $baseDamage
 * @property string $damageGrowth
 * @property int $levels
 * @property string $target {enum self::TARGET_*} {default self::TARGET_SINGLE}
 * @property int $strikes {default 1}
 * @property string|NULL $hitRate
 * @property OneHasMany|CharacterAttackSkill[] $characterSkills {1:m CharacterAttackSkill::$skill}
 */
class SkillAttack extends \Nextras\Orm\Entity\Entity {
  const TARGET_SINGLE = "single";
  const TARGET_ROW = "row";
  const TARGET_COLUMN = "column";
  
  /**
   * @param int $value
   * @return int
   */
  protected function setterStrikes(int $value): int {
    if($value < 1) {
      return 1;
    } elseif($value > 9) {
      return 9;
    } else {
      return $value;
    }
  }
}
?>