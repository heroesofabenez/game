<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nette\Localization\Translator;
use Nextras\Orm\Relationships\OneHasMany;
use Nexendrie\Utils\Numbers;

/**
 * QuestArea
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property-read string $name {virtual}
 * @property-read string $description {virtual}
 * @property int $requiredLevel {default 0}
 * @property CharacterRace|null $requiredRace {m:1 CharacterRace::$areas}
 * @property CharacterClass|null $requiredClass {m:1 CharacterClass::$areas}
 * @property int|null $posX
 * @property int|null $posY
 * @property QuestStage|null $entryStage {m:1 QuestStage, oneSided=true}
 * @property OneHasMany|QuestStage[] $stages {1:m QuestStage::$area}
 * @property OneHasMany|RoutesArea[] $routesOutgoing {1:m RoutesArea::$from}
 * @property OneHasMany|RoutesArea[] $routesIncoming {1:m RoutesArea::$to}
 * @property OneHasMany|ChatMessage[] $chatMessages {1:m ChatMessage::$area}
 */
final class QuestArea extends \Nextras\Orm\Entity\Entity {
  private Translator $translator;

  public function injectTranslator(Translator $translator): void {
    $this->translator = $translator;
  }

  protected function getterName(): string {
    return $this->translator->translate("areas.$this->id.name");
  }

  protected function getterDescription(): string {
    return $this->translator->translate("areas.$this->id.description");
  }

  protected function setterRequiredLevel(int $value): int {
    return Numbers::range($value, 0, 99);
  }
}
?>