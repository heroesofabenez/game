<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * RoutesAreasRepository
 *
 * @author Jakub Konečný
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