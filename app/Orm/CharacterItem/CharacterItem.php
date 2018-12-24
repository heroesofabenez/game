<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nexendrie\Utils\Numbers;

/**
 * CharacterItem
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$items}
 * @property Item $item {m:1 Item, oneSided=true}
 * @property int $amount {default 1}
 * @property bool $worn {default 0}
 * @property int $durability
 * @property-read int $maxDurability {virtual}
 */
final class CharacterItem extends \Nextras\Orm\Entity\Entity {
  protected function setterWorn(bool $value): bool {
    if(!in_array($this->item->slot, Item::getEquipmentTypes(), true)) {
      return false;
    }
    return $value;
  }

  protected function setterDurability(int $value): int {
    return Numbers::range($value, 0, $this->maxDurability);
  }

  protected function getterMaxDurability(): int {
    return $this->item->durability;
  }

  public function onBeforeInsert(): void {
    parent::onBeforeInsert();
    $this->durability = $this->item->durability;
  }

}
?>