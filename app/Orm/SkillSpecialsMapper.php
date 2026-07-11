<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * SkillSpecialsMapper
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Mapper\Dbal\DbalMapper<SkillSpecial>
 */
final class SkillSpecialsMapper extends \Nextras\Orm\Mapper\Dbal\DbalMapper
{
    public function getTableName(): string
    {
        return "skills_specials";
    }
}
