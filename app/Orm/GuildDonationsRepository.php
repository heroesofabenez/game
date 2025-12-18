<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * GuildDonationsRepository
 *
 * @author Jakub Konečný
 * @method GuildDonation|null getById(int $id)
 * @method GuildDonation|null getBy(array $conds)
 * @method ICollection|GuildDonation[] findBy(array $conds)
 * @method ICollection|GuildDonation[] findAll()
 */
final class GuildDonationsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [GuildDonation::class];
    }
}
