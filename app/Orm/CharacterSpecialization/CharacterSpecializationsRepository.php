<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterSpecializationsRepository
 *
 * @author Jakub Konečný
 */
class CharacterSpecializationsRepository extends \Nextras\Orm\Repository\Repository {
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
}
?>