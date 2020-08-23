<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterSpecialSkillsRepository
 *
 * @author Jakub Konečný
 * @method CharacterSpecialSkill|null getById(int $id)
 * @method CharacterSpecialSkill|null getBy(array $conds)
 * @method ICollection|CharacterSpecialSkill[] findBy(array $conds)
 * @method ICollection|CharacterSpecialSkill[] findAll()
 */
final class CharacterSpecialSkillsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [CharacterSpecialSkill::class];
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