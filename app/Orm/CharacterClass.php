<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nette\Localization\Translator;
use Nextras\Orm\Relationships\OneHasMany;
use Nexendrie\Utils\Numbers;

/**
 * CharacterClass
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property-read string $name {virtual}
 * @property-read string $description {virtual}
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
 * @property bool $playable {default true}
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
 * @property OneHasMany|Npc[] $npcs {1:m Npc::$class}
 * @property-read string $mainStat {virtual}
 */
final class CharacterClass extends \Nextras\Orm\Entity\Entity
{
    private const MIN_STATS = -5;
    private const MAX_STATS = 5;

    private Translator $translator;

    public function injectTranslator(Translator $translator): void
    {
        $this->translator = $translator;
    }

    protected function getterName(): string
    {
        return $this->translator->translate("classes.$this->id.name");
    }

    protected function getterDescription(): string
    {
        return $this->translator->translate("classes.$this->id.description");
    }

    protected function setterStrength(int $value): int
    {
        return Numbers::range($value, self::MIN_STATS, self::MAX_STATS);
    }

    protected function setterDexterity(int $value): int
    {
        return Numbers::range($value, self::MIN_STATS, self::MAX_STATS);
    }

    protected function setterConstitution(int $value): int
    {
        return Numbers::range($value, self::MIN_STATS, self::MAX_STATS);
    }

    protected function setterIntelligence(int $value): int
    {
        return Numbers::range($value, self::MIN_STATS, self::MAX_STATS);
    }

    protected function setterCharisma(int $value): int
    {
        return Numbers::range($value, self::MIN_STATS, self::MAX_STATS);
    }

    protected function getterMainStat(): string
    {
        $stats = [
            "strength" => $this->strength, "dexterity" => $this->dexterity, "constitution" => $this->constitution,
            "intelligence" => $this->intelligence, "charisma" => $this->charisma,
        ];
        return (string) array_search(max($stats), $stats, true);
    }
}
