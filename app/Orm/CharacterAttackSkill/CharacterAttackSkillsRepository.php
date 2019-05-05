<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterAttackSkillsRepository
 *
 * @author Jakub Konečný
 */
final class CharacterAttackSkillsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [CharacterAttackSkill::class];
  }
  
  /**
   * @param int $id
   * @return CharacterAttackSkill|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
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