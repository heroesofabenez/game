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
  public static function getEntityClassNames(): array {
    return [Item::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?Item {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>