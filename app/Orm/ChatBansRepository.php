<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * ChatBansRepository
 *
 * @author Jakub Konečný
 * @method ChatBan|null getById(int $id)
 * @method ChatBan|null getBy(array $conds)
 * @method ICollection|ChatBan[] findBy(array $conds)
 * @method ICollection|ChatBan[] findAll()
 */
final class ChatBansRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [ChatBan::class];
    }
}
