<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nette\Localization\ITranslator;
use Nextras\Orm\Relationships\OneHasMany;
use Nexendrie\Utils\Numbers;

/**
 * Quest
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property-read string $name {virtual}
 * @property-read string $introduction {virtual}
 * @property-read string $endText {virtual}
 * @property int $requiredLevel {default 1}
 * @property CharacterClass|null $requiredClass {default null} {m:1 CharacterClass, oneSided=true}
 * @property CharacterRace|null $requiredRace {default null} {m:1 CharacterRace, oneSided=true}
 * @property Quest|null $requiredQuest {m:1 Quest::$children}
 * @property OneHasMany|Quest[] $children {1:m Quest::$requiredQuest}
 * @property Item|null $neededItem {m:1 Item::$neededForQuests}
 * @property int $itemAmount {default 1}
 * @property bool $itemLose {default true}
 * @property int $neededMoney {default 0}
 * @property int $neededArenaWins {default 0}
 * @property int $neededGuildDonation {default 0}
 * @property int $neededActiveSkillsLevel {default 0}
 * @property int $neededFriends {default 0}
 * @property int $rewardMoney
 * @property int $rewardXp
 * @property int $rewardWhiteKarma {default 0}
 * @property int $rewardDarkKarma {default 0}
 * @property Item|null $rewardItem {m:1 Item::$rewardedForQuests}
 * @property PetType|null $rewardPet {m:1 PetType::$rewardedForQuests}
 * @property Npc $npcStart {m:1 Npc::$startQuests}
 * @property Npc $npcEnd {m:1 Npc::$endQuests}
 * @property bool $progress {virtual}
 */
final class Quest extends \Nextras\Orm\Entity\Entity {
  private ITranslator $translator;

  public function injectTranslator(ITranslator $translator): void {
    $this->translator = $translator;
  }

  protected function getterName(): string {
    return $this->translator->translate("quests.$this->id.name");
  }

  protected function getterIntroduction(): string {
    return $this->translator->translate("quests.$this->id.introduction");
  }

  protected function getterEndText(): string {
    return $this->translator->translate("quests.$this->id.end_text");
  }

  protected function setterNeededMoney(int $value): int {
    return Numbers::range($value, 0, 999);
  }

  protected function setterNeededArenaWins(int $value): int {
    return Numbers::range($value, 0, 999);
  }

  protected function setterNeededActiveSkillsLevel(int $value): int {
    return Numbers::range($value, 0, 999);
  }

  protected function setterNeededFriends(int $value): int {
    return Numbers::range($value, 0, 99);
  }

  protected function setterRewardMoney(int $value): int {
    return Numbers::range($value, 0, 9999);
  }

  protected function setterRewardXp(int $value): int {
    return Numbers::range($value, 0, 9999);
  }

  protected function setterRewardWhiteKarma(int $value): int {
    return $this->rewardWhiteKarma = Numbers::range($value, 0, 99);
  }

  protected function setterRewardDarkKarma(int $value): int {
    return $this->rewardDarkKarma = Numbers::range($value, 0, 99);
  }
}
?>