<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * QuestsRepository
 *
 * @author Jakub Konečný
 */
class QuestsRepository extends \Nextras\Orm\Repository\Repository {
  static function getEntityClassNames(): array {
    return [Quest::class];
  }
  
  /**
   * @param int $id
   * @return Quest|NULL
   */
  function getById($id): ?Quest {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>