<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Item
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $description
 * @property string $image
 * @property int $price
 */
class Item extends \Nextras\Orm\Entity\Entity {
  /**
   * @param int $value
   * @return int
   */
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