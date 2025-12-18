<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nette\Localization\Translator;
use Nextras\Orm\Relationships\OneHasMany;
use Nexendrie\Utils\Numbers;

/**
 * Npc
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property-read string $name {virtual}
 * @property-read string $description {virtual}
 * @property CharacterRace $race {m:1 CharacterRace::$npcs}
 * @property CharacterClass $class {m:1 CharacterClass::$npcs}
 * @property CharacterSpecialization|null $specialization {m:1 CharacterSpecialization::$npcs}
 * @property-read bool $quests {virtual}
 * @property-read bool $shop {virtual}
 * @property bool $fight {default false}
 * @property bool $smith {default false}
 * @property string $sprite
 * @property string $portrait
 * @property QuestStage $stage {m:1 QuestStage::$npcs}
 * @property string $karma {enum \HeroesofAbenez\Utils\Karma::KARMA_*}
 * @property string $personality {enum static::PERSONALITY_*}
 * @property int $level {default 1}
 * @property int $posX {default 1}
 * @property int $posY {default 1}
 * @property OneHasMany|ShopItem[] $items {1:m ShopItem::$npc, orderBy=order}
 * @property OneHasMany|Quest[] $startQuests {1:m Quest::$npcStart}
 * @property OneHasMany|Quest[] $endQuests {1:m Quest::$npcEnd}
 */
final class Npc extends \Nextras\Orm\Entity\Entity
{
    public const PERSONALITY_FRIENDLY = "friendly";
    public const PERSONALITY_CRAZY = "crazy";
    public const PERSONALITY_SHY = "shy";
    public const PERSONALITY_HOSTILE = "hostile";
    public const PERSONALITY_RESERVED = "reserved";
    public const PERSONALITY_ELITIST = "elitist";
    public const PERSONALITY_TEACHING = "teaching";
    public const PERSONALITY_RACIST = "racist";
    public const PERSONALITY_MISOGYNIST = "misogynist";

    private Translator $translator;

    public function injectTranslator(Translator $translator): void
    {
        $this->translator = $translator;
    }

    protected function getterName(): string
    {
        return $this->translator->translate("npcs.$this->id.name");
    }

    protected function getterDescription(): string
    {
        return $this->translator->translate("npcs.$this->id.description");
    }

    protected function getterQuests(): bool
    {
        return ($this->startQuests->countStored() > 0 || $this->endQuests->countStored() > 0);
    }

    protected function getterShop(): bool
    {
        return ($this->items->countStored() > 0);
    }

    protected function setterLevel(int $value): int
    {
        return Numbers::range($value, 1, 999);
    }
}
