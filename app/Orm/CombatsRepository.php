<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CombatsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<Combat>
 */
final class CombatsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Combat::class];
    }
}
