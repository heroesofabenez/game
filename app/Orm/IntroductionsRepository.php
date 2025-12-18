<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * IntroductionsRepository
 *
 * @author Jakub Konečný
 * @method Introduction|null getById(int $id)
 * @method Introduction|null getBy(array $conds)
 * @method ICollection|Introduction[] findBy(array $conds)
 * @method ICollection|Introduction[] findAll()
 */
final class IntroductionsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Introduction::class];
    }
}
