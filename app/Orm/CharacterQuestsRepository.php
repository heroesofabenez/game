<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterQuestsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<CharacterQuest>
 */
final class CharacterQuestsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [CharacterQuest::class];
    }

    public function getByCharacterAndQuest(Character|int $character, Quest|int $quest): ?CharacterQuest
    {
        return $this->getBy([
            "character" => $character,
            "quest" => $quest
        ]);
    }

    /**
     * @return ICollection<CharacterQuest>
     */
    public function findByCharacter(Character|int $character): ICollection
    {
        return $this->findBy([
            "character" => $character
        ]);
    }
}
