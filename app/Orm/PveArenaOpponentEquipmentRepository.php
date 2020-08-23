<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * PveArenaOpponentEquipmentRepository
 *
 * @author Jakub Konečný
 * @method PveArenaOpponentEquipment|null getById(int $id)
 * @method PveArenaOpponentEquipment|null getBy(array $conds)
 * @method ICollection|PveArenaOpponentEquipment[] findBy(array $conds)
 * @method ICollection|PveArenaOpponentEquipment[] findAll()
 */
final class PveArenaOpponentEquipmentRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [PveArenaOpponentEquipment::class];
  }
}
?>