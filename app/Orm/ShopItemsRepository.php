<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ShopItemsRepository
 *
 * @author Jakub Konečný
 */
final class ShopItemsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [ShopItem::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?ShopItem {
    return $this->getBy([
      "id" => $id
    ]);
  }

  /**
   * @param Item|int $item
   * @param Npc|int $npc
   * @return ShopItem|null
   */
  public function getByItemAndNpc($item, $npc): ?ShopItem {
    return $this->getBy([
      "item" => $item,
      "npc" => $npc,
    ]);
  }
}
?>