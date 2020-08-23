<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterAttackSkillsRepository
 *
 * @author Jakub Konečný
 * @method CharacterAttackSkill|null getById(int $id)
 * @method CharacterAttackSkill|null getBy(array $conds)
 * @method ICollection|CharacterAttackSkill[] findBy(array $conds)
 * @method ICollection|CharacterAttackSkill[] findAll()
 */
final class CharacterAttackSkillsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [CharacterAttackSkill::class];
  }
  
  /**
   * @param Character|int $character
   * @param SkillAttack|int $skill
   */
  public function getByCharacterAndSkill($character, $skill): ?CharacterAttackSkill {
    return $this->getBy([
      "character" => $character,
      "skill" => $skill
    ]);
  }
}
?>