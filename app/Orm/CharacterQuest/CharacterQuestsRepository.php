<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterQuestsRepository
 *
 * @author Jakub Konečný
 */
final class CharacterQuestsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [CharacterQuest::class];
  }
  
  /**
   * @param int $id
   * @return CharacterQuest|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
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