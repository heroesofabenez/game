<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * RoutesStagesRepository
 *
 * @author Jakub Konečný
 */
class RoutesStagesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [RoutesStage::class];
  }
  
}
?>