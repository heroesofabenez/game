<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterSpecialSkillsRepository
 *
 * @author Jakub Konečný
 */
class CharacterSpecialSkillsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [CharacterSpecialSkill::class];
  }
  
  /**
   * @param int $id
   */
  function getById($id): ?CharacterSpecialSkill {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $character
   * @param SkillSpecial|int $skill
   */
  function getByCharacterAndSkill($character, $skill): ?CharacterSpecialSkill {
    return $this->getBy([
      "character" => $character,
      "skill" => $skill
    ]);
  }
}
?>