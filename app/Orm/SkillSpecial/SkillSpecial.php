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
  const TYPE_BUFF = "buff";
  const TYPE_DEBUFF = "debuff";
  const TYPE_STUN = "stun";
  const TARGET_SELF = "self";
  const TARGET_ENEMY = "enemy";
  const TARGET_PARTY = "party";
  const TARGET_ENEMY_PARTY = "enemy_party";
  const STAT_HP = "hp";
  const STAT_DAMAGE = "damage";
  const STAT_DEFENSE = "defense";
  const STAT_HIT = "hit";
  const STAT_DODGE = "dodge";
  const STAT_INITIATIVE = "initiative";
  
  /**
   * @param string|NULL $value
   * @return string|NULL
   */
  protected function setterStat(?string $value): ?string {
    if(is_null($value)) {
      return NULL;
    } elseif($this->type === static::TYPE_STUN) {
      return NULL;
    } else {
      return $value;
    }
  }
}
?>