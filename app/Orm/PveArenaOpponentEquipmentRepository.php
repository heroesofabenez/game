<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * PveArenaOpponentEquipmentRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<PveArenaOpponentEquipment>
 */
final class PveArenaOpponentEquipmentRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [PveArenaOpponentEquipment::class];
    }
}
