<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * ItemsRepository
 *
 * @author Jakub Konečný
 * @method Item|null getById(int $id)
 * @method Item|null getBy(array $conds)
 * @method ICollection|Item[] findBy(array $conds)
 * @method ICollection|Item[] findAll()
 */
final class ItemsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Item::class];
  }
}
?>