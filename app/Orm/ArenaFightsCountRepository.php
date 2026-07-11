<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ArenaFightsCountRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<ArenaFightCount>
 */
final class ArenaFightsCountRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [ArenaFightCount::class];
    }

    public function getByCharacterAndDay(Character|int $character, string $day): ?ArenaFightCount
    {
        return $this->getBy([
            "character" => $character,
            "day" => $day
        ]);
    }
}
