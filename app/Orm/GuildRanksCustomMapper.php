<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildRanksCustomMapper
 *
 * @author Jakub Konečný
 */
final class GuildRanksCustomMapper extends \Nextras\Orm\Mapper\Dbal\DbalMapper
{
    public function getTableName(): string
    {
        return "guild_ranks_custom";
    }
}
