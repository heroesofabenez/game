<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

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
 */
class CharacterSpecialization extends \Nextras\Orm\Entity\Entity {
  
}
?>