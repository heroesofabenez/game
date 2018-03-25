<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany,
    Nextras\Orm\Entity\ToArrayConverter;

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
 * @property string $target {enum SkillAttackDummy::TARGET_*} {default SkillAttackDummy::TARGET_SINGLE}
 * @property int $strikes {default 1}
 * @property string|NULL $hitRate
 * @property OneHasMany|CharacterAttackSkill[] $characterSkills {1:m CharacterAttackSkill::$skill}
 */
class SkillAttack extends \Nextras\Orm\Entity\Entity {
  public const MAX_STRIKES = 9;
  
  protected function setterStrikes(int $value): int {
    if($value < 1) {
      return 1;
    } elseif($value > static::MAX_STRIKES) {
      return static::MAX_STRIKES;
    }
    return $value;
  }
  
  public function toDummy(): SkillAttackDummy {
    $data = $this->toArray(ToArrayConverter::RELATIONSHIP_AS_ID);
    unset($data["characterSkills"]);
    return new SkillAttackDummy($data);
  }
}
?>