<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * GuildPrivilegesRepository
 *
 * @author Jakub Konečný
 * @method GuildPrivilege|null getById(int $id)
 * @method GuildPrivilege|null getBy(array $conds)
 * @method ICollection|GuildPrivilege[] findBy(array $conds)
 * @method ICollection|GuildPrivilege[] findAll()
 */
final class GuildPrivilegesRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [GuildPrivilege::class];
    }
}
