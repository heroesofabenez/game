<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * ShopItemsRepository
 *
 * @author Jakub Konečný
 */
class ShopItemsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [ShopItem::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?ShopItem {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Npc|int $npc
   * @return ICollection|ShopItem[]
   */
  public function findByNpc($npc): ICollection {
    return $this->findBy([
      "npc" => $npc
    ])->orderBy("order");
  }
}
?>