<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

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
    if($value < 0) {
      return 0;
    } elseif($value > 99) {
      return 99;
    }
    return $value;
  }
}
?>