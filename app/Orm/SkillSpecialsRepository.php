<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * SkillSpecialsRepository
 *
 * @author Jakub Konečný
 * @method SkillSpecial|null getById(int $id)
 * @method SkillSpecial|null getBy(array $conds)
 * @method ICollection|SkillSpecial[] findBy(array $conds)
 * @method ICollection|SkillSpecial[] findAll()
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
     * @return ICollection|SkillSpecial[]
     */
    public function findByClassAndLevel(CharacterClass|int $class, int $level): ICollection
    {
        return $this->findBy([
            "neededClass" => $class,
            "neededLevel<=" => $level
        ]);
    }
}
