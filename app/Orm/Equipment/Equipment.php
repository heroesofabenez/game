<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * Equipment
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $description
 * @property string $slot {enum self::SLOT_*}
 * @property string|NULL $type {enum self::TYPE_*}
 * @property int $requiredLevel {default 1}
 * @property CharacterClass|NULL $requiredClass {m:1 CharacterClass::$equipment}
 * @property int $price {default 0}
 * @property int $strength
 * @property int $durability
 * @property OneHasMany|PveArenaOpponent[] $arenaNpcs {1:m PveArenaOpponent::$weapon}
 * @property OneHasMany|CharacterEquipment[] $characterEquipment {1:m CharacterEquipment::$item}
 */
class Equipment extends \Nextras\Orm\Entity\Entity {
  const SLOT_WEAPON = "weapon";
  const SLOT_ARMOR = "armor";
  const SLOT_SHIELD = "shield";
  const SLOT_AMULET = "amulet";
  const TYPE_SWORD = "sword";
  const TYPE_AXE = "axe";
  const TYPE_CLUB = "club";
  const TYPE_DAGGER = "dagger";
  const TYPE_SPEAR = "spear";
  const TYPE_STAFF = "staff";
  const TYPE_BOW = "bow";
  const TYPE_CROSSBOW = "crossbow";
  const TYPE_THROWING_KNIFE = "throwing knife";
  
  /**
   * @param string $value
   * @return string|NULL
   */
  protected function setterType(string $value): ?string {
    if($this->slot !== static::SLOT_WEAPON) {
      return NULL;
    } else {
      return $value;
    }
  }
}
?>