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
 * @property string $slot {enum \HeroesofAbenez\Combat\Equipment::SLOT_*}
 * @property string|null $type {enum \HeroesofAbenez\Combat\Weapon::TYPE_*}
 * @property int $requiredLevel {default 1}
 * @property CharacterClass|null $requiredClass {m:1 CharacterClass::$equipment}
 * @property int $price {default 0}
 * @property int $strength
 * @property int $durability
 * @property OneHasMany|PveArenaOpponent[] $arenaNpcsWeapon {1:m PveArenaOpponent::$weapon}
 * @property OneHasMany|PveArenaOpponent[] $arenaNpcsArmor {1:m PveArenaOpponent::$armor}
 * @property OneHasMany|CharacterEquipment[] $characterEquipment {1:m CharacterEquipment::$item}
 * @property bool $worn Is the item worn? {virtual}
 */
final class Equipment extends \Nextras\Orm\Entity\Entity {
  protected function setterType(string $value): ?string {
    if($this->slot !== \HeroesofAbenez\Combat\Equipment::SLOT_WEAPON) {
      return null;
    }
    return $value;
  }
  
  public function toCombatEquipment(): \HeroesofAbenez\Combat\Equipment {
    $data = [];
    $stats = ["id", "name", "slot", "type", "strength", "worn",];
    foreach($stats as $stat) {
      $data[$stat] = $this->$stat;
    }
    if($data["slot"] === \HeroesofAbenez\Combat\Equipment::SLOT_WEAPON) {
      return new \HeroesofAbenez\Combat\Weapon($data);
    }
    return new \HeroesofAbenez\Combat\Equipment($data);
  }
}
?>