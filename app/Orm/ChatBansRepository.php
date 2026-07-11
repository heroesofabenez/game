<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ChatBansRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<ChatBan>
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
