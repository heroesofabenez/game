<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterItemsRepository
 *
 * @author Jakub Konečný
 */
class CharacterItemsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [CharacterItem::class];
  }
  
  /**
   * @param int $id
   * @return CharacterItem|NULL
   */
  function getById($id): ?CharacterItem {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $character
   * @param Item|int $item
   * @return CharacterItem|NULL
   */
  function getByCharacterAndItem($character, $item): ?CharacterItem {
    return $this->getBy([
      "character" => $character,
      "item" => $item
    ]);
  }
}
?>