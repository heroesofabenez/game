<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * PveArenaOpponentsRepository
 *
 * @author Jakub Konečný
 */
final class PveArenaOpponentsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [PveArenaOpponent::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?PveArenaOpponent {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>