<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * RoutesAreasRepository
 *
 * @author Jakub Konečný
 * @method RoutesArea|null getById(int $id)
 * @method RoutesArea|null getBy(array $conds)
 * @method ICollection|RoutesArea[] findBy(array $conds)
 * @method ICollection|RoutesArea[] findAll()
 */
final class RoutesAreasRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [RoutesArea::class];
  }
}
?>