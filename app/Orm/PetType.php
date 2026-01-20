<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nette\Localization\Translator;
use Nextras\Orm\Relationships\OneHasMany;
use Nexendrie\Utils\Numbers;

/**
 * PetType
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property-read string $name {virtual}
 * @property string $bonusStat {enum self::STAT_*}
 * @property int $bonusValue
 * @property string $image
 * @property int $requiredLevel
 * @property CharacterClass|null $requiredClass {m:1 CharacterClass::$petTypes}
 * @property CharacterRace|null $requiredRace {m:1 CharacterRace::$petTypes}
 * @property int $cost {default 0}
 * @property OneHasMany|Pet[] $pets {1:m Pet::$type}
 * @property OneHasMany|Quest[] $rewardedForQuests {1:m Quest::$rewardPet}
 */
final class PetType extends \Nextras\Orm\Entity\Entity
{
    public const string STAT_STR = "strength";
    public const string STAT_DEX = "dexterity";
    public const string STAT_CON = "constitution";
    public const string STAT_INT = "intelligence";

    private Translator $translator;

    public function injectTranslator(Translator $translator): void
    {
        $this->translator = $translator;
    }

    protected function getterName(): string
    {
        return $this->translator->translate("pets.$this->id.name");
    }

    protected function setterBonusValue(int $value): int
    {
        return Numbers::clamp($value, 0, 99);
    }

    protected function setterRequiredLevel(int $value): int
    {
        return Numbers::clamp($value, 0, 99);
    }
}
