<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * PveArenaOpponentsRepository
 *
 * @author Jakub Konečný
 * @method PveArenaOpponent|null getById(int $id)
 * @method PveArenaOpponent|null getBy(array $conds)
 * @method ICollection|PveArenaOpponent[] findBy(array $conds)
 * @method ICollection|PveArenaOpponent[] findAll()
 */
final class PveArenaOpponentsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [PveArenaOpponent::class];
  }
}
?>