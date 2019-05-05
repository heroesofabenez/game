<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * PveArenaOpponentEquipmentRepository
 *
 * @author Jakub Konečný
 */
final class PveArenaOpponentEquipmentRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [PveArenaOpponentEquipment::class];
  }
  
  /**
   * @param int $id
   * @return PveArenaOpponentEquipment
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>