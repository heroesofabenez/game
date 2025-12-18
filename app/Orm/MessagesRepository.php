<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * MessagesRepository
 *
 * @author Jakub Konečný
 * @method Message|null getById(int $id)
 * @method Message|null getBy(array $conds)
 * @method ICollection|Message[] findBy(array $conds)
 * @method ICollection|Message[] findAll()
 */
final class MessagesRepository extends \Nextras\Orm\Repository\Repository
{
    public static function getEntityClassNames(): array
    {
        return [Message::class];
    }

    /**
     * @return ICollection|Message[]
     */
    public function findByFrom(Character|int $user): ICollection
    {
        return $this->findBy([
            "from" => $user
        ]);
    }

    /**
     * @return ICollection|Message[]
     */
    public function findByTo(Character|int $user): ICollection
    {
        return $this->findBy([
            "to" => $user
        ]);
    }
}
