<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * FriendshipsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<Friendship>
 */
final class FriendshipsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Friendship::class];
    }

    /**
     * @return ICollection<Friendship>
     */
    public function findByCharacter(Character|int $character): ICollection
    {
        return $this->findBy([
            ICollection::OR,
            "character1" => $character,
            "character2" => $character,
        ]);
    }
}
