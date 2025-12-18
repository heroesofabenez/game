<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterSpecializationsRepository
 *
 * @author Jakub Konečný
 * @method CharacterSpecialization|null getById(int $id)
 * @method CharacterSpecialization|null getBy(array $conds)
 * @method ICollection|CharacterSpecialization[] findBy(array $conds)
 * @method ICollection|CharacterSpecialization[] findAll()
 */
final class CharacterSpecializationsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [CharacterSpecialization::class];
    }

    /**
     * @return ICollection|CharacterSpecialization[]
     */
    public function findByClass(int|CharacterClass $class): ICollection
    {
        return $this->findBy(["class" => $class]);
    }
}
