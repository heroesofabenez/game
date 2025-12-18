<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * ChatMessagesRepository
 *
 * @author Jakub Konečný
 * @method ChatMessage|null getById(int $id)
 * @method ChatMessage|null getBy(array $conds)
 * @method ICollection|ChatMessage[] findBy(array $conds)
 * @method ICollection|ChatMessage[] findAll()
 */
final class ChatMessagesRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [ChatMessage::class];
    }
}
