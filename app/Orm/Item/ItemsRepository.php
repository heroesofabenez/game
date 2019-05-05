<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ItemsRepository
 *
 * @author Jakub Konečný
 */
final class ItemsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Item::class];
  }
  
  /**
   * @param int $id
   * @return Item|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>