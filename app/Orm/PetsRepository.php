<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * PetsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<Pet>
 */
final class PetsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Pet::class];
    }

    /**
     * @return ICollection<Pet>
     */
    public function findByOwner(Character|int $owner): ICollection
    {
        return $this->findBy([
            "owner" => $owner
        ]);
    }

    public function getByTypeAndOwner(PetType|int $type, Character|int $owner): ?Pet
    {
        return $this->getBy([
            "type" => $type,
            "owner" => $owner
        ]);
    }
}
