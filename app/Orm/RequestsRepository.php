<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * RequstsRepository
 *
 * @author Jakub Konečný
 * @method Request|null getById(int $id)
 * @method Request|null getBy(array $conds)
 * @method ICollection|Request[] findBy(array $conds)
 * @method ICollection|Request[] findAll()
 */
final class RequestsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Request::class];
    }

    public function getUnresolvedFriendshipRequest(Character|int $character1, Character|int $character2): ?Request
    {
        return $this->getBy([
            ICollection::OR,
            [
                "from" => $character1,
                "to" => $character2,
                "type" => Request::TYPE_FRIENDSHIP,
                "status" => Request::STATUS_NEW,
            ],
            [
                "from" => $character2,
                "to" => $character1,
                "type" => Request::TYPE_FRIENDSHIP,
                "status" => Request::STATUS_NEW,
            ],
        ]);
    }

    /**
     * @return ICollection|Request[]
     */
    public function findUnresolvedGuildApplications(Character|int $guildLeader): ICollection
    {
        return $this->findBy([
            "to" => $guildLeader,
            "type" => Request::TYPE_GUILD_APP,
            "status" => Request::STATUS_NEW,
        ]);
    }
}
