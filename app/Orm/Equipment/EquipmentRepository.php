<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * EquipmentRepository
 *
 * @author Jakub Konečný
 */
class EquipmentRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [Equipment::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?Equipment {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>