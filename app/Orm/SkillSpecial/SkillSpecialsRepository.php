<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * SkillSpecialsRepository
 *
 * @author Jakub Konečný
 */
class SkillSpecialsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [SkillSpecial::class];
  }
  
  /**
   * @param int $id
   * @return SkillSpecial|NULL
   */
  function getById($id): ?SkillSpecial {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param CharacterClass|int $class
   * @param int $level
   * @return ICollection|SkillSpecial
   */
  function findByClassAndLevel($class, int $level): ICollection {
    return $this->findBy([
      "neededClass" => $class,
      "neededLevel>=" => $level
    ]);
  }
}
?>