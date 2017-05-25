<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ArenaFightsCountMapper
 *
 * @author Jakub Konečný
 */
class ArenaFightsCountMapper extends \Nextras\Orm\Mapper\Mapper {
  function getTableName(): string {
    return "arena_fights_count";
  }
}
?>