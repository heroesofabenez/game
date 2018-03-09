<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

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
 * @property string $type {enum self::TYPE_*}
 * @property string $target {enum self::TARGET_*}
 * @property string|NULL $stat {enum self::STAT_*}
 * @property int $value {default 0}
 * @property int $valueGrowth
 * @property int $levels
 * @property int $duration
 * @property OneHasMany|CharacterSpecialSkill[] $characterSkills {1:m CharacterSpecialSkill::$skill}
 */
class SkillSpecial extends \Nextras\Orm\Entity\Entity {
  public const TYPE_BUFF = "buff";
  public const TYPE_DEBUFF = "debuff";
  public const TYPE_STUN = "stun";
  public const TYPE_POISON = "poison";
  public const TARGET_SELF = "self";
  public const TARGET_ENEMY = "enemy";
  public const TARGET_PARTY = "party";
  public const TARGET_ENEMY_PARTY = "enemy_party";
  public const STAT_HP = "hp";
  public const STAT_DAMAGE = "damage";
  public const STAT_DEFENSE = "defense";
  public const STAT_HIT = "hit";
  public const STAT_DODGE = "dodge";
  public const STAT_INITIATIVE = "initiative";
  /** @var string[] */
  public const NO_STAT_TYPES = [self::TYPE_STUN, self::TYPE_POISON,];
  
  protected function setterStat(?string $value): ?string {
    if(in_array($value, static::NO_STAT_TYPES, true)) {
      return NULL;
    }
    return $value;
  }
}
?>