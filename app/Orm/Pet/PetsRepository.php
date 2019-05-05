<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * PetsRepository
 *
 * @author Jakub Konečný
 */
final class PetsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Pet::class];
  }
  
  /**
   * @param int $id
   * @return Pet|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $owner
   * @return ICollection|Pet[]
   */
  public function findByOwner($owner): ICollection {
    return $this->findBy([
      "owner" => $owner
    ]);
  }

  /**
   * @param PetType|int $type
   * @param Character|int $owner
   */
  public function getByTypeAndOwner($type, $owner): ?Pet {
    return $this->getBy([
      "type" => $type,
      "owner" => $owner
    ]);
  }
}
?>