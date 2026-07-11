<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * SkillSpecialsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<SkillSpecial>
 */
final class SkillSpecialsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [SkillSpecial::class];
    }

    /**
     * @return ICollection<SkillSpecial>
     */
    public function findByClassAndLevel(CharacterClass|int $class, int $level): ICollection
    {
        return $this->findBy([
            "neededClass" => $class,
            "neededLevel<=" => $level
        ]);
    }
}
