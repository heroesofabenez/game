<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * PetTypesRepository
 *
 * @author Jakub Konečný
 */
final class PetTypesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
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