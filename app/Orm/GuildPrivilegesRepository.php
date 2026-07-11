<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildPrivilegesRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<GuildPrivilege>
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
