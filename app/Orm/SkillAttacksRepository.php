<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * SkillAttacksRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<SkillAttack>
 */
final class SkillAttacksRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [SkillAttack::class];
    }

    /**
     * @return ICollection<SkillAttack>
     */
    public function findByClassAndLevel(CharacterClass|int $class, int $level): ICollection
    {
        return $this->findBy([
            "neededClass" => $class,
            "neededLevel<=" => $level
        ]);
    }
}
