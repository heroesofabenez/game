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
   */
  function getById($id): ?CharacterAttackSkill {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $character
   * @param SkillAttack|int $skill
   */
  function getByCharacterAndSkill($character, $skill): ?CharacterAttackSkill {
    return $this->getBy([
      "character" => $character,
      "skill" => $skill
    ]);
  }
}
?>