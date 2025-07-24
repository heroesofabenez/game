<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * ShopItemsRepository
 *
 * @author Jakub Konečný
 * @method ShopItem|null getById(int $id)
 * @method ShopItem|null getBy(array $conds)
 * @method ICollection|ShopItem[] findBy(array $conds)
 * @method ICollection|ShopItem[] findAll()
 */
final class ShopItemsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [ShopItem::class];
  }

  public function getByItemAndNpc(Item|int $item, Npc|int $npc): ?ShopItem {
    return $this->getBy([
      "item" => $item,
      "npc" => $npc,
    ]);
  }
}
?>