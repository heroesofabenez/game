<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * QuestAreasRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<QuestArea>
 */
final class QuestAreasRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [QuestArea::class];
    }
}
