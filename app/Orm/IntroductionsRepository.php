<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * IntroductionsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<Introduction>
 */
final class IntroductionsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Introduction::class];
    }
}
