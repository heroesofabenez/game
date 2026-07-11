<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * PetTypesRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<PetType>
 */
final class PetTypesRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [PetType::class];
    }
}
