<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterAttackSkillsRepository
 *
 * @author Jakub Konečný
 */
class CharacterAttackSkillsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [CharacterAttackSkill::class];
  }
  
  /**
   * @param int $id
   * @return CharacterAttackSkill|NULL
   */
  function getById($id): ?CharacterAttackSkill {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $character
   * @param SkillAttack|int $skill
   * @return CharacterAttackSkill|NULL
   */
  function getByCharacterAndSkill($character, $skill): ?CharacterAttackSkill {
    return $this->getBy([
      "character" => $character,
      "skill" => $skill
    ]);
  }
}
?>