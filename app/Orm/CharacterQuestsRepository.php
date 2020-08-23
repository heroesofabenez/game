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
final class CharacterQuestsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [CharacterQuest::class];
  }
  
  /**
   * @param Character|int $character
   * @param Quest|int $quest
   */
  public function getByCharacterAndQuest($character, $quest): ?CharacterQuest {
    return $this->getBy([
      "character" => $character,
      "quest" => $quest
    ]);
  }
  
  /**
   * @param Character|int $character
   * @return ICollection|CharacterQuest[]
   */
  public function findByCharacter($character): ICollection {
    return $this->findBy([
      "character" => $character
    ]);
  }
}
?>