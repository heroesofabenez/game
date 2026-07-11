<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * IntroductionsMapper
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Mapper\Dbal\DbalMapper<Introduction>
 */
final class IntroductionsMapper extends \Nextras\Orm\Mapper\Dbal\DbalMapper
{
    public function getTableName(): string
    {
        return "introduction";
    }
}
