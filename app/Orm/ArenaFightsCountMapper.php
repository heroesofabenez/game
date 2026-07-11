<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ArenaFightsCountMapper
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Mapper\Dbal\DbalMapper<ArenaFightCount>
 */
final class ArenaFightsCountMapper extends \Nextras\Orm\Mapper\Dbal\DbalMapper
{
    public function getTableName(): string
    {
        return "arena_fights_count";
    }
}
