<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * QuestsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<Quest>
 */
final class QuestsRepository extends \Nextras\Orm\Repository\Repository
{
    public static function getEntityClassNames(): array
    {
        return [Quest::class];
    }
}
