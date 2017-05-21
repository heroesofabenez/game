<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * RoutesAreasRepository
 *
 * @author Jakub Konečný
 */
class RoutesAreasRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [RoutesArea::class];
  }
  
}
?>