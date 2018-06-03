<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * EquipmentMapper
 *
 * @author Jakub Konečný
 */
final class EquipmentMapper extends \Nextras\Orm\Mapper\Mapper {
  public function getTableName(): string {
    return "equipment";
  }
}
?>