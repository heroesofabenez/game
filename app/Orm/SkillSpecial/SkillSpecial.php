<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;
use Nextras\Orm\Entity\ToArrayConverter;
use HeroesofAbenez\Combat\SkillSpecial as SkillSpecialDummy;
use HeroesofAbenez\Combat\Character as CharacterDummy;

/**
 * SkillSpecial
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $description
 * @property CharacterClass $neededClass {m:1 CharacterClass::$specialSkills}
 * @property CharacterSpecialization|null $neededSpecialization {m:1 CharacterSpecialization::$specialSkills}
 * @property int $neededLevel
 * @property string $type {enum SkillSpecialDummy::TYPE_*}
 * @property string $target {enum SkillSpecialDummy::TARGET_*}
 * @property string|null $stat {enum static::STAT_*}
 * @property int $value {default 0}
 * @property int $valueGrowth
 * @property int $levels
 * @property int $duration
 * @property OneHasMany|CharacterSpecialSkill[] $characterSkills {1:m CharacterSpecialSkill::$skill}
 */
final class SkillSpecial extends \Nextras\Orm\Entity\Entity {
  /** @internal */
  public const STAT_MAX_HITPOINTS = CharacterDummy::STAT_MAX_HITPOINTS;
  /** @internal */
  public const STAT_DAMAGE = CharacterDummy::STAT_DAMAGE;
  /** @internal */
  public const STAT_DEFENSE = CharacterDummy::STAT_DEFENSE;
  /** @internal */
  public const STAT_HIT = CharacterDummy::STAT_HIT;
  /** @internal */
  public const STAT_DODGE = CharacterDummy::STAT_DODGE;
  /** @internal */
  public const STAT_INITIATIVE = CharacterDummy::STAT_INITIATIVE;
  
  protected function setterStat(?string $value): ?string {
    if(in_array($value, SkillSpecialDummy::NO_STAT_TYPES, true)) {
      return null;
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