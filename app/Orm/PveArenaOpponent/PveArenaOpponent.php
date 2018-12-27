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
 * @property int $level {default 1}
 * @property-read int $strength {virtual}
 * @property-read int $dexterity {virtual}
 * @property-read int $constitution {virtual}
 * @property-read int $intelligence {virtual}
 * @property-read int $charisma {virtual}
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

  protected function calculateMainStatGrow(string $stat): int {
    if($stat === $this->class->mainStat) {
      return (int) (($this->level - 1) * $this->class->statPointsLevel);
    }
    return 0;
  }

  protected function getterStrength(): int {
    $base = $this->class->strength + $this->race->strength;
    $growth = (int) ($this->class->strengthGrow * ($this->level - 1));
    $growth += $this->calculateMainStatGrow("strength");
    return $base + $growth;
  }

  protected function getterDexterity(): int {
    $base = $this->class->dexterity + $this->race->dexterity;
    $growth = (int) ($this->class->dexterityGrow * ($this->level - 1));
    $growth += $this->calculateMainStatGrow("dexterity");
    return $base + $growth;
  }

  protected function getterConstitution(): int {
    $base = $this->class->constitution + $this->race->constitution;
    $growth = (int) ($this->class->constitutionGrow * ($this->level - 1));
    $growth += $this->calculateMainStatGrow("constitution");
    return $base + $growth;
  }

  protected function getterIntelligence(): int {
    $base = $this->class->intelligence + $this->race->intelligence;
    $growth = (int) ($this->class->intelligenceGrow * ($this->level - 1));
    $growth += $this->calculateMainStatGrow("intelligence");
    return $base + $growth;
  }

  protected function getterCharisma(): int {
    $base = $this->class->charisma + $this->race->charisma;
    $growth = (int) ($this->class->charismaGrow * ($this->level - 1));
    $growth += $this->calculateMainStatGrow("charisma");
    return $base + $growth;
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