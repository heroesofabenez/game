<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;
use Nexendrie\Utils\Numbers;

/**
 * CharacterClass
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property int $strength
 * @property float $strengthGrow
 * @property int $dexterity
 * @property float $dexterityGrow
 * @property int $constitution
 * @property float $constitutionGrow
 * @property int $intelligence
 * @property float $intelligenceGrow
 * @property int $charisma
 * @property float $charismaGrow
 * @property float $statPointsLevel
 * @property string $initiative
 * @property OneHasMany|CharacterSpecialization[] $specializations {1:m CharacterSpecialization::$class}
 * @property OneHasMany|PetType[] $petTypes {1:m PetType::$requiredClass}
 * @property OneHasMany|QuestArea[] $areas {1:m QuestArea::$requiredClass}
 * @property OneHasMany|QuestStage[] $stages {1:m QuestStage::$requiredClass}
 * @property OneHasMany|Character[] $characters {1:m Character::$class}
 * @property OneHasMany|Introduction[] $intro {1:m Introduction::$class}
 * @property OneHasMany|PveArenaOpponent[] $arenaNpcs {1:m PveArenaOpponent::$class}
 * @property OneHasMany|Item[] $items {1:m Item::$requiredClass}
 * @property OneHasMany|SkillAttack[] $attackSkills {1:m SkillAttack::$neededClass}
 * @property OneHasMany|SkillSpecial[] $specialSkills {1:m SkillSpecial::$neededClass}
 * @property-read string $mainStat {virtual}
 */
final class CharacterClass extends \Nextras\Orm\Entity\Entity {
  protected const MAX_STATS = 99;
  
  protected function setterStrength(int $value): int {
    return Numbers::range($value, 0, static::MAX_STATS);
  }
  
  protected function setterDexterity(int $value): int {
    return Numbers::range($value, 0, static::MAX_STATS);
  }
  
  protected function setterConstitution(int $value): int {
    return Numbers::range($value, 0, static::MAX_STATS);
  }
  
  protected function setterIntelligence(int $value): int {
    return Numbers::range($value, 0, static::MAX_STATS);
  }
  
  protected function setterCharisma(int $value): int {
    return Numbers::range($value, 0, static::MAX_STATS);
  }

  protected function getterMainStat(): string {
    $stats = [
      "strength" => $this->strength, "dexterity" => $this->dexterity, "constitution" => $this->constitution,
      "intelligence" => $this->intelligence, "charisma" => $this->charisma,
    ];
    return array_search(max($stats), $stats, true);
  }
}
?>