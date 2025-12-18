<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nexendrie\Utils\Numbers;

/**
 * Introduction
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property CharacterRace $race {m:1 CharacterRace::$intro}
 * @property CharacterClass $class {m:1 CharacterClass::$intro}
 * @property int $part
 * @property string $text
 */
final class Introduction extends \Nextras\Orm\Entity\Entity
{
    protected function setterPart(int $value): int
    {
        return Numbers::range($value, 1, 9);
    }
}
