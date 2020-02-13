<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * SkillSpecialsRepository
 *
 * @author Jakub Konečný
 */
final class SkillSpecialsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [SkillSpecial::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?SkillSpecial {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param CharacterClass|int $class
   * @return ICollection|SkillSpecial[]
   */
  public function findByClassAndLevel($class, int $level): ICollection {
    return $this->findBy([
      "neededClass" => $class,
      "neededLevel<=" => $level
    ]);
  }
}
?>