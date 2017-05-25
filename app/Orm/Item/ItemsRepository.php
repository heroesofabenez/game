<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ItemsRepository
 *
 * @author Jakub Konečný
 */
class ItemsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [Item::class];
  }
  
  /**
   * @param int $id
   * @return Item|NULL
   */
  function getById($id): ?Item {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>