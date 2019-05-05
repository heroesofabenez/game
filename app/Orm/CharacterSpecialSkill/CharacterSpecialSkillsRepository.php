<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterSpecialSkillsRepository
 *
 * @author Jakub Konečný
 */
final class CharacterSpecialSkillsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [CharacterSpecialSkill::class];
  }
  
  /**
   * @param int $id
   * @return CharacterSpecialSkill|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $character
   * @param SkillSpecial|int $skill
   */
  public function getByCharacterAndSkill($character, $skill): ?CharacterSpecialSkill {
    return $this->getBy([
      "character" => $character,
      "skill" => $skill
    ]);
  }
}
?>