<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterSpecializationsRepository
 *
 * @author Jakub Konečný
 */
final class CharacterSpecializationsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [CharacterSpecialization::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?CharacterSpecialization {
    return $this->getBy([
      "id" => $id
    ]);
  }

  /**
   * @param int|CharacterClass $class
   * @return ICollection|CharacterSpecialization[]
   */
  public function findByClass($class): ICollection {
    return $this->findBy(["class" => $class]);
  }
}
?>