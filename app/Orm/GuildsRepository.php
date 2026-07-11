<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<Guild>
 */
final class GuildsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Guild::class];
    }

    public function getByName(string $name): ?Guild
    {
        return $this->getBy([
            "name" => $name
        ]);
    }
}
