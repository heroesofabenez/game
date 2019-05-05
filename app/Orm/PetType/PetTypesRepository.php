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
   * @return PetType|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>