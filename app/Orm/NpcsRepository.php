<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * NpcsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<Npc>
 */
final class NpcsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Npc::class];
    }

    /**
     * @return ICollection<Npc>
     */
    public function findByStage(QuestStage|int $stage): ICollection
    {
        return $this->findBy([
            "stage" => $stage
        ]);
    }
}
