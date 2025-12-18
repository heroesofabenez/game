<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CombatsRepository
 *
 * @author Jakub Konečný
 * @method Combat|null getById(int $id)
 * @method Combat|null getBy(array $conds)
 * @method ICollection|Combat[] findBy(array $conds)
 * @method ICollection|Combat[] findAll()
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
