<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany,
    Nextras\Orm\Entity\ToArrayConverter,
    HeroesofAbenez\Combat\SkillSpecial as SkillSpecialDummy;

/**
 * SkillSpecial
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $description
 * @property CharacterClass $neededClass {m:1 CharacterClass::$specialSkills}
 * @property CharacterSpecialization|NULL $neededSpecialization {m:1 CharacterSpecialization::$specialSkills}
 * @property int $neededLevel
 * @property string $type {enum SkillSpecialDummy::TYPE_*}
 * @property string $target {enum SkillSpecialDummy::TARGET_*}
 * @property string|NULL $stat {enum SkillSpecialDummy::STAT_*}
 * @property int $value {default 0}
 * @property int $valueGrowth
 * @property int $levels
 * @property int $duration
 * @property OneHasMany|CharacterSpecialSkill[] $characterSkills {1:m CharacterSpecialSkill::$skill}
 */
class SkillSpecial extends \Nextras\Orm\Entity\Entity {
  protected function setterStat(?string $value): ?string {
    if(in_array($value, SkillSpecialDummy::NO_STAT_TYPES, true)) {
      return NULL;
    }
    return $value;
  }
  
  public function toDummy(): SkillSpecialDummy {
    $data = $this->toArray(ToArrayConverter::RELATIONSHIP_AS_ID);
    unset($data["characterSkills"]);
    return new SkillSpecialDummy($data);
  }
}
?>