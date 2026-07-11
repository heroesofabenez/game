<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterClassesRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<CharacterClass>
 */
final class CharacterClassesRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [CharacterClass::class];
    }
}
