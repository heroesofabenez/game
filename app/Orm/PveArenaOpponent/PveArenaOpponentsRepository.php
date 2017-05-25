<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * PveArenaOpponentsRepository
 *
 * @author Jakub Konečný
 */
class PveArenaOpponentsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [PveArenaOpponent::class];
  }
  
  /**
   * @param int $id
   * @return PveArenaOpponent|NULL
   */
  function getById($id): ?PveArenaOpponent {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>