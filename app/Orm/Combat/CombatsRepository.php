<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CombatsRepository
 *
 * @author Jakub Konečný
 */
class CombatsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [Combat::class];
  }
  
  /**
   * @param int $id
   * @return Combat|NULL
   */
  function getById($id): ?Combat {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>