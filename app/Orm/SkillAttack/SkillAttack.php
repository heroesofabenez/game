<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;
use Nextras\Orm\Entity\ToArrayConverter;
use HeroesofAbenez\Combat\SkillAttack as SkillAttackDummy;
use Nexendrie\Utils\Numbers;

/**
 * SkillAttack
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $description
 * @property CharacterClass $neededClass {m:1 CharacterClass::$attackSkills}
 * @property CharacterSpecialization|null $neededSpecialization {m:1 CharacterSpecialization::$attackSkills}
 * @property int $neededLevel
 * @property string $baseDamage
 * @property string $damageGrowth
 * @property int $levels
 * @property string $target {enum SkillAttackDummy::TARGET_*} {default SkillAttackDummy::TARGET_SINGLE}
 * @property int $strikes {default 1}
 * @property string|null $hitRate
 * @property OneHasMany|CharacterAttackSkill[] $characterSkills {1:m CharacterAttackSkill::$skill}
 */
final class SkillAttack extends \Nextras\Orm\Entity\Entity {
  public const MAX_STRIKES = 9;
  
  protected function setterStrikes(int $value): int {
    return Numbers::range($value, 1, static::MAX_STRIKES);
  }
  
  public function toDummy(): SkillAttackDummy {
    $data = $this->toArray(ToArrayConverter::RELATIONSHIP_AS_ID);
    unset($data["characterSkills"], $data["description"], $data["neededClass"], $data["neededSpecialization"], $data["neededLevel"]);
    return new SkillAttackDummy($data);
  }
}
?>