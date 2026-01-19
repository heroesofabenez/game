<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nexendrie\Utils\Numbers;

/**
 * CharacterItem
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$items}
 * @property Item $item {m:1 Item, oneSided=true}
 * @property int $amount {default 1}
 * @property bool $worn {default 0}
 * @property int $durability
 * @property-read int $maxDurability {virtual}
 * @property-read int $buyPrice {virtual}
 * @property-read int $repairPrice {virtual}
 */
final class CharacterItem extends \Nextras\Orm\Entity\Entity
{
    protected function setterWorn(bool $value): bool
    {
        if (!in_array($this->item->slot, Item::getEquipmentTypes(), true)) {
            return false;
        }
        return $value;
    }

    protected function setterDurability(int $value): int
    {
        return Numbers::clamp($value, 0, $this->maxDurability);
    }

    protected function getterMaxDurability(): int
    {
        return $this->item->durability;
    }

    protected function getterBuyPrice(): int
    {
        return (int) ($this->item->price - ($this->item->price / 100 * $this->character->charismaBonus));
    }

    protected function getterRepairPrice(): int
    {
        $price = ($this->item->price > 0) ? $this->item->price : 8;
        $damage = ($this->durability / $this->maxDurability * 100 - 100) * -1;
        $repairPrice = ($price / 100 * $damage * $this->amount);
        return (int) ($repairPrice - $repairPrice / 100 * $this->character->charismaBonus);
    }

    public function toCombatEquipment(): ?\HeroesofAbenez\Combat\Equipment
    {
        $this->item->worn = $this->worn;
        $equipment = $this->item->toCombatEquipment();
        if ($equipment !== null) {
            $equipment->durability = $this->durability;
        }
        return $equipment;
    }

    public function onBeforeInsert(): void
    {
        parent::onBeforeInsert();
        $this->durability = $this->item->durability;
    }
}
