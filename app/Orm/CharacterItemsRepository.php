<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterItemsRepository
 *
 * @author Jakub Konečný
 * @method CharacterItem|null getById(int $id)
 * @method CharacterItem|null getBy(array $conds)
 * @method ICollection|CharacterItem[] findBy(array $conds)
 * @method ICollection|CharacterItem[] findAll()
 */
final class CharacterItemsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [CharacterItem::class];
  }

  public function getByCharacterAndItem(Character|int $character, Item|int $item): ?CharacterItem {
    return $this->getBy([
      "character" => $character,
      "item" => $item
    ]);
  }

  /**
   * @return ICollection|CharacterItem[]
   */
  public function findByCharacterAndSlot(Character|int $character, string $slot): ICollection {
    return $this->findBy([
      "character" => $character,
      "item->slot" => $slot
    ]);
  }

  /**
   * @return ICollection|CharacterItem[]
   */
  public function findCharactersEquipment(Character|int $character): ICollection {
    return $this->findBy([
      "character" => $character,
      "worn" => true
    ]);
  }
}
?>