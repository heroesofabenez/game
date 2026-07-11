<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ItemsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<Item>
 */
final class ItemsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Item::class];
    }
}
