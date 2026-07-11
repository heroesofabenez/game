<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterSpecializationsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<CharacterSpecialization>
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
     * @return ICollection<CharacterSpecialization>
     */
    public function findByClass(int|CharacterClass $class): ICollection
    {
        return $this->findBy(["class" => $class]);
    }
}
