<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * SkillAttacksRepository
 *
 * @author Jakub Konečný
 */
final class SkillAttacksRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [SkillAttack::class];
  }
  
  /**
   * @param int $id
   * @return SkillAttack|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
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