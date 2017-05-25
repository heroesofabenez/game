<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * CharacterSpecialization
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property CharacterClass $class {m:1 CharacterClass::$specializations}
 * @property float $strengthGrow
 * @property float $dexterityGrow
 * @property float $constitutionGrow
 * @property float $intelligenceGrow
 * @property float $charismaGrow
 * @property float $statPointsLevel
 * @property OneHasMany|Character[] $characters {1:m Character::$specialization}
 * @property OneHasMany|SkillAttack[] $attackSkills {1:m SkillAttack::$neededSpecialization}
 * @property OneHasMany|SkillSpecial[] $specialSkills {1:m SkillSpecial::$neededSpecialization}
 */
class CharacterSpecialization extends \Nextras\Orm\Entity\Entity {
  
}
?>