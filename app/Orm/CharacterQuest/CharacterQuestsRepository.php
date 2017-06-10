<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterQuestsRepository
 *
 * @author Jakub Konečný
 */
class CharacterQuestsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [CharacterQuest::class];
  }
  
  /**
   * @param int $id
   * @return CharacterQuest|NULL
   */
  function getById($id): ?CharacterQuest {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $character
   * @param Quest|int $quest
   * @return CharacterQuest|NULL
   */
  function getByCharacterAndQuest($character, $quest): ?CharacterQuest {
    return $this->getBy([
      "character" => $character,
      "quest" => $quest
    ]);
  }
  
  /**
   * @param Character|int $character
   * @return ICollection|CharacterQuest[]
   */
  function findByCharacter($character): ICollection {
    return $this->findBy([
      "character" => $character
    ]);
  }
}
?>