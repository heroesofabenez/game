<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * PetsRepository
 *
 * @author Jakub Konečný
 */
class PetsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [Pet::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?Pet {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $owner
   */
  public function getActivePet($owner): ?Pet {
    return $this->getBy([
      "owner" => $owner,
      "deployed" => true
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
}
?>