<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterAttackSkill
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$attackSkills}
 * @property SkillAttack $skill {m:1 SkillAttack::$characterSkills}
 * @property int $level {default 1}
 */
class CharacterAttackSkill extends \Nextras\Orm\Entity\Entity {
  
}
?>