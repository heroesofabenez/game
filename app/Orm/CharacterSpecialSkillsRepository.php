<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterSpecialSkillsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<CharacterSpecialSkill>
 */
final class CharacterSpecialSkillsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [CharacterSpecialSkill::class];
    }

    public function getByCharacterAndSkill(Character|int $character, SkillSpecial|int $skill): ?CharacterSpecialSkill
    {
        return $this->getBy([
            "character" => $character,
            "skill" => $skill
        ]);
    }
}
