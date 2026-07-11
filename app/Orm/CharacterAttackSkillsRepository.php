<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterAttackSkillsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<CharacterAttackSkill>
 */
final class CharacterAttackSkillsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [CharacterAttackSkill::class];
    }

    public function getByCharacterAndSkill(Character|int $character, SkillAttack|int $skill): ?CharacterAttackSkill
    {
        return $this->getBy([
            "character" => $character,
            "skill" => $skill
        ]);
    }
}
