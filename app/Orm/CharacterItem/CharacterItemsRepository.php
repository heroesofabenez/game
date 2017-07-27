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
   */
  public function getById($id): ?CharacterItem {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $character
   * @param Item|int $item
   */
  public function getByCharacterAndItem($character, $item): ?CharacterItem {
    return $this->getBy([
      "character" => $character,
      "item" => $item
    ]);
  }
}
?>