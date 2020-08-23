<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * QuestAreasRepository
 *
 * @author Jakub Konečný
 * @method QuestArea|null getById(int $id)
 * @method QuestArea|null getBy(array $conds)
 * @method ICollection|QuestArea[] findBy(array $conds)
 * @method ICollection|QuestArea[] findAll()
 */
final class QuestAreasRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [QuestArea::class];
  }
}
?>