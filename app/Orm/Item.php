<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nette\Localization\Translator;
use Nextras\Orm\Relationships\OneHasMany;
use Nexendrie\Utils\Numbers;
use Nexendrie\Utils\Constants;

/**
 * Item
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property-read string $name {virtual}
 * @property-read string $description {virtual}
 * @property string $slot {enum static::SLOT_*}
 * @property string|null $type {enum \HeroesofAbenez\Combat\Weapon::TYPE_*}
 * @property int $requiredLevel {default 1}
 * @property CharacterClass|null $requiredClass {m:1 CharacterClass::$items}
 * @property CharacterSpecialization|null $requiredSpecialization {m:1 CharacterSpecialization::$items}
 * @property int $price
 * @property int $strength
 * @property int $durability
 * @property bool $worn Is the item worn? {virtual}
 * @property-read bool $equipable Can the item be equipped? {virtual}
 * @property OneHasMany|ShopItem[] $inShops {1:m ShopItem::$item}
 * @property OneHasMany|Quest[] $neededForQuests {1:m Quest::$neededItem}
 * @property OneHasMany|Quest[] $rewardedForQuests {1:m Quest::$rewardItem}
 */
final class Item extends \Nextras\Orm\Entity\Entity
{
    public const SLOT_ITEM = "item";
    public const SLOT_WEAPON = \HeroesofAbenez\Combat\Equipment::SLOT_WEAPON;
    public const SLOT_ARMOR = \HeroesofAbenez\Combat\Equipment::SLOT_ARMOR;
    public const SLOT_SHIELD = \HeroesofAbenez\Combat\Equipment::SLOT_SHIELD;
    public const SLOT_AMULET = \HeroesofAbenez\Combat\Equipment::SLOT_AMULET;
    public const SLOT_HELMET = \HeroesofAbenez\Combat\Equipment::SLOT_HELMET;
    public const SLOT_RING = \HeroesofAbenez\Combat\Equipment::SLOT_RING;

    private Translator $translator;

    public function injectTranslator(Translator $translator): void
    {
        $this->translator = $translator;
    }

    protected function getterName(): string
    {
        return $this->translator->translate("items.$this->id.name");
    }

    protected function getterDescription(): string
    {
        return $this->translator->translate("items.$this->id.description");
    }

    protected function getterEquipable(): bool
    {
        return in_array($this->slot, self::getEquipmentTypes(), true);
    }

    protected function setterPrice(int $value): int
    {
        return Numbers::clamp($value, 0, 999);
    }

    protected function setterType(string $value): ?string
    {
        if ($this->slot !== \HeroesofAbenez\Combat\Equipment::SLOT_WEAPON) {
            return null;
        }
        return $value;
    }

    public static function getEquipmentTypes(): array
    {
        return Constants::getConstantsValues(\HeroesofAbenez\Combat\Equipment::class, "SLOT_");
    }

    public function toCombatEquipment(): ?\HeroesofAbenez\Combat\Equipment
    {
        if (!in_array($this->slot, self::getEquipmentTypes(), true)) {
            return null;
        }
        $data = [];
        $stats = ["id", "name", "slot", "type", "strength", "worn",];
        foreach ($stats as $stat) {
            $data[$stat] = $this->$stat;
        }
        $data["maxDurability"] = $this->durability;
        if ($data["slot"] === \HeroesofAbenez\Combat\Equipment::SLOT_WEAPON) {
            return new \HeroesofAbenez\Combat\Weapon($data);
        }
        return new \HeroesofAbenez\Combat\Equipment($data);
    }
}
