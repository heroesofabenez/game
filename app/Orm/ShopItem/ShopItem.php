<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nexendrie\Utils\Numbers;

/**
 * ShopItem
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Npc $npc {m:1 Npc::$items}
 * @property Item $item {m:1 Item::$inShops}
 * @property int $order
 */
class ShopItem extends \Nextras\Orm\Entity\Entity {
  protected function setterOrder(int $value): int {
    return Numbers::range($value, 0, 99);
  }
}
?>