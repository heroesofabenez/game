<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * Item
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $description
 * @property string $image
 * @property int $price
 * @property OneHasMany|ShopItem[] $inShops {1:m ShopItem::$item}
 * @property OneHasMany|CharacterItem[] $characterItems {1:m CharacterItem::$item}
 * @property OneHasMany|Quest[] $neededForQuests {1:m Quest::$neededItem}
 * @property OneHasMany|Quest[] $rewardedForQuests {1:m Quest::$rewardItem}
 */
class Item extends \Nextras\Orm\Entity\Entity {
  protected function setterPrice(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > 999) {
      return 999;
    } else {
      return $value;
    }
  }
}
?>