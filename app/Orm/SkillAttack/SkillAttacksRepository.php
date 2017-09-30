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
  public static function getEntityClassNames(): array {
    return [SkillAttack::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?SkillAttack {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param CharacterClass|int $class
   * @return ICollection|SkillAttack
   */
  public function findByClassAndLevel($class, int $level): ICollection {
    return $this->findBy([
      "neededClass" => $class,
      "neededLevel>=" => $level
    ]);
  }
}
?>