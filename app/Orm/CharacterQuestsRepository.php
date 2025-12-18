<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterQuestsRepository
 *
 * @author Jakub Konečný
 * @method CharacterQuest|null getById(int $id)
 * @method CharacterQuest|null getBy(array $conds)
 * @method ICollection|CharacterQuest[] findBy(array $conds)
 * @method ICollection|CharacterQuest[] findAll()
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
     * @return ICollection|CharacterQuest[]
     */
    public function findByCharacter(Character|int $character): ICollection
    {
        return $this->findBy([
            "character" => $character
        ]);
    }
}
