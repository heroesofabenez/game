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
 * @property-read array $deployParams Deploy params of the equipment {virtual}
 * @property bool $worn Is the item worn? {virtual}
 */
class Equipment extends \Nextras\Orm\Entity\Entity {
  public const SLOT_WEAPON = "weapon";
  public const SLOT_ARMOR = "armor";
  public const SLOT_SHIELD = "shield";
  public const SLOT_AMULET = "amulet";
  public const TYPE_SWORD = "sword";
  public const TYPE_AXE = "axe";
  public const TYPE_CLUB = "club";
  public const TYPE_DAGGER = "dagger";
  public const TYPE_SPEAR = "spear";
  public const TYPE_STAFF = "staff";
  public const TYPE_BOW = "bow";
  public const TYPE_CROSSBOW = "crossbow";
  public const TYPE_THROWING_KNIFE = "throwing knife";
  
  protected function setterType(string $value): ?string {
    if($this->slot !== static::SLOT_WEAPON) {
      return NULL;
    }
    return $value;
  }
  
  protected function getterDeployParams(): array {
    $stat = [
      "weapon" => "damage", "armor" => "defense", "shield" => "dodge", "amulet" => "initiative"
    ];
    $return = [
      "id" => "equipment" . $this->id . "bonusEffect",
      "type" => "buff",
      "stat" => $stat[$this->slot],
      "value" => $this->strength,
      "source" => "equipment",
      "duration" => "combat"
    ];
    return $return;
  }
}
?>