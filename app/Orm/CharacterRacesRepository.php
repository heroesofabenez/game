<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterRacesRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<CharacterRace>
 */
final class CharacterRacesRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [CharacterRace::class];
    }
}
