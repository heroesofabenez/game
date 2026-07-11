<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * PveArenaOpponentsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<PveArenaOpponent>
 */
final class PveArenaOpponentsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [PveArenaOpponent::class];
    }
}
