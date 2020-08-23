<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * RoutesStagesRepository
 *
 * @author Jakub Konečný
 * @method RoutesStage|null getById(int $id)
 * @method RoutesStage|null getBy(array $conds)
 * @method ICollection|RoutesStage[] findBy(array $conds)
 * @method ICollection|RoutesStage[] findAll()
 */
final class RoutesStagesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [RoutesStage::class];
  }
}
?>