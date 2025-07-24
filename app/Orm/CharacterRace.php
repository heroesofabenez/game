<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nette\Localization\Translator;
use Nextras\Orm\Relationships\OneHasMany;
use Nexendrie\Utils\Numbers;

/**
 * CharacterRace
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property-read string $name {virtual}
 * @property-read string $description {virtual}
 * @property int $strength {default 0}
 * @property int $dexterity {default 0}
 * @property int $constitution {default 0}
 * @property int $intelligence {default 0}
 * @property int $charisma {default 0}
 * @property bool $playable {default true}
 * @property OneHasMany|PetType[] $petTypes {1:m PetType::$requiredRace}
 * @property OneHasMany|QuestArea[] $areas {1:m QuestArea::$requiredRace}
 * @property OneHasMany|QuestStage[] $stages {1:m QuestStage::$requiredRace}
 * @property OneHasMany|Npc[] $npcs {1:m Npc::$race}
 * @property OneHasMany|Character[] $characters {1:m Character::$race}
 * @property OneHasMany|Introduction[] $intro {1:m Introduction::$race}
 * @property OneHasMany|PveArenaOpponent[] $arenaNpcs {1:m PveArenaOpponent::$race}
 */
final class CharacterRace extends \Nextras\Orm\Entity\Entity {
  private const MIN_STATS = 0;
  private const MAX_STATS = 15;

  private Translator $translator;

  public function injectTranslator(Translator $translator): void {
    $this->translator = $translator;
  }

  protected function getterName(): string {
    return $this->translator->translate("races.$this->id.name");
  }

  protected function getterDescription(): string {
    return $this->translator->translate("races.$this->id.description");
  }
  
  protected function setterStrength(int $value): int {
    return Numbers::range($value, static::MIN_STATS, static::MAX_STATS);
  }
  
  protected function setterDexterity(int $value): int {
    return Numbers::range($value, static::MIN_STATS, static::MAX_STATS);
  }
  
  protected function setterConstitution(int $value): int {
    return Numbers::range($value, static::MIN_STATS, static::MAX_STATS);
  }
  
  protected function setterIntelligence(int $value): int {
    return Numbers::range($value, static::MIN_STATS, static::MAX_STATS);
  }
  
  protected function setterCharisma(int $value): int {
    return Numbers::range($value, static::MIN_STATS, static::MAX_STATS);
  }
}
?>