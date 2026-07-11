<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ShopItemsRepository
 *
 * @author Jakub Konečný
 * @extends \Nextras\Orm\Repository\Repository<ShopItem>
 */
final class ShopItemsRepository extends \Nextras\Orm\Repository\Repository
{
    /**
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [ShopItem::class];
    }

    public function getByItemAndNpc(Item|int $item, Npc|int $npc): ?ShopItem
    {
        return $this->getBy([
            "item" => $item,
            "npc" => $npc,
        ]);
    }
}
