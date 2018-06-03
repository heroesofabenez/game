<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterEquipmentMapper
 *
 * @author Jakub Konečný
 */
final class CharacterEquipmentMapper extends \Nextras\Orm\Mapper\Mapper {
  public function getTableName(): string {
    return "character_equipment";
  }
}
?>