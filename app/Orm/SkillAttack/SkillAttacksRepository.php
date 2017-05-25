<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * SkillAttacksRepository
 *
 * @author Jakub Konečný
 */
class SkillAttacksRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [SkillAttack::class];
  }
  
  /**
   * @param int $id
   * @return SkillAttack|NULL
   */
  function getById($id): ?SkillAttack {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param CharacterClass|int $class
   * @param int $level
   * @return ICollection|SkillAttack
   */
  function findByClassAndLevel($class, int $level): ICollection {
    return $this->findBy([
      "neededClass" => $class,
      "neededLevel>=" => $level
    ]);
  }
}
?>