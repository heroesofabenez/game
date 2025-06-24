<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * PveArenaOpponentEquipmentMapper
 *
 * @author Jakub Konečný
 */
final class PveArenaOpponentEquipmentMapper extends \Nextras\Orm\Mapper\Dbal\DbalMapper {
  public function getTableName(): string {
    return "pve_arena_opponent_equipment";
  }
}
?>