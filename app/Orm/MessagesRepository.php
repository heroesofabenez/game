<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * MessagesRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<Message>
 */
final class MessagesRepository extends \Nextras\Orm\Repository\Repository
{
    public static function getEntityClassNames(): array
    {
        return [Message::class];
    }

    /**
     * @return ICollection<Message>
     */
    public function findByFrom(Character|int $user): ICollection
    {
        return $this->findBy([
            "from" => $user
        ]);
    }

    /**
     * @return ICollection<Message>
     */
    public function findByTo(Character|int $user): ICollection
    {
        return $this->findBy([
            "to" => $user
        ]);
    }
}
