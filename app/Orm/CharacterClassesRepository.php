<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterClassesRepository
 *
 * @author Jakub Konečný
 * @method CharacterClass|null getById(int $id)
 * @method CharacterClass|null getBy(array $conds)
 * @method ICollection|CharacterClass[] findBy(array $conds)
 * @method ICollection|CharacterClass[] findAll()
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
