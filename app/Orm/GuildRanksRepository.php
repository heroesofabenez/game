<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildRanksRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<GuildRank>
 */
final class GuildRanksRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [GuildRank::class];
    }
}
