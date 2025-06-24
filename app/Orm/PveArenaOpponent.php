<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use HeroesofAbenez\Combat\CharacterSkillsCollection;
use Nexendrie\Utils\Numbers;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Relationships\OneHasMany;
use HeroesofAbenez\Combat\BaseCharacterSkill;

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
 * @property-read BaseCharacterSkill[] $skills {virtual}
 */
final class PveArenaOpponent extends \Nextras\Orm\Entity\Entity {
  public const GENDER_MALE = "male";
  public const GENDER_FEMALE = "female";

  protected function setterLevel(int $value): int {
    return Numbers::range($value, 1, 999);
  }

  protected function getterWeapon(): ?Item {
    // @phpstan-ignore return.type
    return $this->class->items->toCollection()->orderBy("requiredLevel", ICollection::DESC)
      ->limitBy(1)
      ->getBy([
        ICollection::OR,
        [
          "requiredLevel<=" => $this->level,
          "slot" => Item::SLOT_WEAPON,
          "requiredSpecialization" => null,
        ],
        [
          "requiredLevel<=" => $this->level,
          "slot" => Item::SLOT_WEAPON,
          "requiredSpecialization" => $this->specialization,
        ]
      ]);
  }

  protected function getterArmor(): ?Item {
    // @phpstan-ignore return.type
    return $this->class->items->toCollection()->orderBy("requiredLevel", ICollection::DESC)
      ->limitBy(1)
      ->getBy([
        ICollection::OR,
        [
          "requiredLevel<=" => $this->level,
          "slot" => Item::SLOT_ARMOR,
          "requiredSpecialization" => null,
        ],
        [
          "requiredLevel<=" => $this->level,
          "slot" => Item::SLOT_ARMOR,
          "requiredSpecialization" => $this->specialization,
        ]
      ]);
  }

  /**
   * @return BaseCharacterSkill[]
   */
  protected function getterSkills(): array {
    if($this->level === 1) {
      return [];
    }
    $skills = new CharacterSkillsCollection();
    /** @var ICollection|SkillAttack[] $attackSkills */
    $attackSkills = $this->class->attackSkills->toCollection()->findBy([
      ICollection::OR,
      [
        "neededLevel<=" => $this->level,
        "neededSpecialization" => null,
      ],
      [
        "neededLevel<=" => $this->level,
        "neededSpecialization" => $this->specialization,
      ]
    ]);
    foreach($attackSkills as $skill) {
      $skills[] = new \HeroesofAbenez\Combat\CharacterAttackSkill($skill->toDummy(), 0);
    }
    /** @var ICollection|SkillSpecial[] $specialSkills */
    $specialSkills = $this->class->specialSkills->toCollection()->findBy([
      ICollection::OR,
      [
        "neededLevel<=" => $this->level,
        "neededSpecialization" => null,
      ],
      [
        "neededLevel<=" => $this->level,
        "neededSpecialization" => $this->specialization,
      ]
    ]);
    foreach($specialSkills as $skill) {
      $skills[] = new \HeroesofAbenez\Combat\CharacterSpecialSkill($skill->toDummy(), 0);
    }
    $skillPoints = $this->level - 1;
    for($i = 1; $skillPoints > 0; $i++) {
      foreach($skills as $skill) {
        if($skillPoints < 1) {
          break;
        }
        if($skill->level + 1 <= $skill->skill->levels) {
          $skill->level++;
        }
        $skillPoints--;
      }
    }
    return $skills->getItems(["level>" => 0]);
  }
}
?>