<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharactersRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<Character>
 */
final class CharactersRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Character::class];
    }

    public function getByName(string $name): ?Character
    {
        return $this->getBy([
            "name" => $name
        ]);
    }

    public function getByOwner(int $owner): ?Character
    {
        return $this->getBy([
            "owner" => $owner
        ]);
    }

    /**
     * @return ICollection<Character>
     */
    public function findByGuild(Guild|int $guild): ICollection
    {
        return $this->findBy([
            "guild" => $guild
        ])->orderBy("guildrank", ICollection::DESC)
            ->orderBy("id");
    }
}
