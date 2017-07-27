<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildRanksCustomMapper
 *
 * @author Jakub Konečný
 */
class GuildRanksCustomMapper extends \Nextras\Orm\Mapper\Mapper {
  public function getTableName(): string {
    return "guild_ranks_custom";
  }
}
?>