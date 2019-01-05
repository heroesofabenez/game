<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nexendrie\Utils\Numbers;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Relationships\OneHasMany;

/**
 * PveArenaOpponent
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property CharacterRace $race {m:1 CharacterRace::$arenaNpcs}
 * @property string $gender {enum self::GENDER_*} {default self::GENDER_MALE}
 * @property CharacterClass $class {m:1 CharacterClass::$arenaNpcs}
 * @property CharacterSpecialization|null $specialization {m:1 CharacterSpecialization::$arenaNpcs}
 * @property int $level {default 1}
 * @property OneHasMany|PveArenaOpponentEquipment[] $equipment {1:m PveArenaOpponentEquipment::$npc}
 * @property-read Item|null $weapon {virtual}
 * @property-read Item|null $armor {virtual}
 */
final class PveArenaOpponent extends \Nextras\Orm\Entity\Entity {
  public const GENDER_MALE = "male";
  public const GENDER_FEMALE = "female";

  protected function setterLevel(int $value): int {
    return Numbers::range($value, 1, 999);
  }

  protected function getterWeapon(): ?Item {
    return $this->class->items->get()->orderBy("requiredLevel", ICollection::DESC)
      ->getBy([
        "requiredLevel<=" => $this->level,
        "slot" => Item::SLOT_WEAPON,
      ]);
  }

  protected function getterArmor(): ?Item {
    return $this->class->items->get()->orderBy("requiredLevel", ICollection::DESC)
      ->getBy([
        "requiredLevel<=" => $this->level,
        "slot" => Item::SLOT_ARMOR,
        ]);
  }
}
?>