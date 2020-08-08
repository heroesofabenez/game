<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterItemsRepository
 *
 * @author Jakub Konečný
 */
final class CharacterItemsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
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

  /**
   * @param Character|int $character
   * @return ICollection|CharacterItem[]
   */
  public function findByCharacterAndSlot($character, string $slot): ICollection {
    return $this->findBy([
      "character" => $character,
      "this->item->slot" => $slot
    ]);
  }

  /**
   * @param Character|int $character
   * @return ICollection|CharacterItem[]
   */
  public function findCharactersEquipment($character): ICollection {
    return $this->findBy([
      "character" => $character,
      "worn" => true
    ]);
  }
}
?>