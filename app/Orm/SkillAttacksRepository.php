<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * SkillAttacksRepository
 *
 * @author Jakub Konečný
 * @method SkillAttack|null getById(int $id)
 * @method SkillAttack|null getBy(array $conds)
 * @method ICollection|SkillAttack[] findBy(array $conds)
 * @method ICollection|SkillAttack[] findAll()
 */
final class SkillAttacksRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [SkillAttack::class];
  }
  
  /**
   * @param CharacterClass|int $class
   * @return ICollection|SkillAttack[]
   */
  public function findByClassAndLevel($class, int $level): ICollection {
    return $this->findBy([
      "neededClass" => $class,
      "neededLevel<=" => $level
    ]);
  }
}
?>