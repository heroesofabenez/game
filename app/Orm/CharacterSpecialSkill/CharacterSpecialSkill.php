<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterSpecialSkill
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$specialSkills}
 * @property SkillSpecial $skill {m:1 SkillSpecial::$characterSkills}
 * @property int $level {default 1}
 */
class CharacterSpecialSkill extends \Nextras\Orm\Entity\Entity {
  
}
?>