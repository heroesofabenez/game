<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany,
    Nexendrie\Utils\Numbers;

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
    return Numbers::range($value, 0, 999);
  }
}
?>