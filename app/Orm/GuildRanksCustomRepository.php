<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * GuildRanksCustomRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<GuildRankCustom>
 */
final class GuildRanksCustomRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [GuildRankCustom::class];
    }

    public function getByGuildAndRank(Guild|int $guild, GuildRank|int $rank): ?GuildRankCustom
    {
        return $this->getBy([
            "guild" => $guild, "rank" => $rank
        ]);
    }

    /**
     * @return ICollection<GuildRankCustom>
     */
    public function findByGuild(Guild|int $guild): ICollection
    {
        return $this->findBy([
            "guild" => $guild
        ]);
    }
}
