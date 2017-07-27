<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * PetTypesRepository
 *
 * @author Jakub Konečný
 */
class PetTypesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [PetType::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?PetType {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>