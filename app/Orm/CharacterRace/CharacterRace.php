<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * CharacterRace
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string|NULL $description
 * @property int $strength {default 0}
 * @property int $dexterity {default 0}
 * @property int $constitution {default 0}
 * @property int $intelligence {default 0}
 * @property int $charisma {default 0}
 * @property OneHasMany|PetType[] $petTypes {1:m PetType::$requiredRace}
 * @property OneHasMany|QuestArea[] $areas {1:m QuestArea::$requiredRace}
 * @property OneHasMany|QuestStage[] $stages {1:m QuestStage::$requiredRace}
 * @property OneHasMany|Npc[] $npcs {1:m Npc::$race}
 * @property OneHasMany|Character[] $characters {1:m Character::$race}
 * @property OneHasMany|Introduction[] $intro {1:m Introduction::$race}
 * @property OneHasMany|PveArenaOpponent[] $arenaNpcs {1:m PveArenaOpponent::$race}
 */
class CharacterRace extends \Nextras\Orm\Entity\Entity {
  const MAX_STATS = 99;
  
  protected function setterStrength(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > static::MAX_STATS) {
      return static::MAX_STATS;
    }
    return $value;
  }
  
  protected function setterDexterity(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > static::MAX_STATS) {
      return static::MAX_STATS;
    }
    return $value;
  }
  
  protected function setterConstitution(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > static::MAX_STATS) {
      return static::MAX_STATS;
    }
    return $value;
  }
  
  protected function setterIntelligence(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > static::MAX_STATS) {
      return static::MAX_STATS;
    }
    return $value;
  }
  
  protected function setterCharisma(int $value): int {
    if($value < 0) {
      return 0;
    } elseif($value > static::MAX_STATS) {
      return static::MAX_STATS;
    }
    return $value;
  }
}
?>