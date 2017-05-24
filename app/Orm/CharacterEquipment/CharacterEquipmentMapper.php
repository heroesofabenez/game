<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterEquipmentMapper
 *
 * @author Jakub Konečný
 */
class CharacterEquipmentMapper extends \Nextras\Orm\Mapper\Mapper {
  function getTableName(): string {
    return "character_equipment";
  }
}
?>