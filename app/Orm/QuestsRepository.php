<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * QuestsRepository
 *
 * @author Jakub Konečný
 * @method Quest|null getById(int $id)
 * @method Quest|null getBy(array $conds)
 * @method ICollection|Quest[] findBy(array $conds)
 * @method ICollection|Quest[] findAll()
 */
final class QuestsRepository extends \Nextras\Orm\Repository\Repository
{
    public static function getEntityClassNames(): array
    {
        return [Quest::class];
    }
}
