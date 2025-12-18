<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * QuestStagesRepository
 *
 * @author Jakub Konečný
 * @method QuestStage|null getById(int $id)
 * @method QuestStage|null getBy(array $conds)
 * @method ICollection|QuestStage[] findBy(array $conds)
 * @method ICollection|QuestStage[] findAll()
 */
final class QuestStagesRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [QuestStage::class];
    }

    /**
     * @return ICollection|QuestStage[]
     */
    public function findByArea(QuestArea|int $area): ICollection
    {
        return $this->findBy([
            "area" => $area
        ]);
    }

    public function getClassStartingLocation(CharacterClass|int $class): ?QuestStage
    {
        return $this->getBy([
            "requiredLevel" => 0,
            "requiredClass" => $class
        ]);
    }

    public function getRaceStartingLocation(CharacterRace|int $race): ?QuestStage
    {
        return $this->getBy([
            "requiredLevel" => 0,
            "requiredRace" => $race
        ]);
    }
}
