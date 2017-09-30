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
  public static function getEntityClassNames(): array {
    return [Combat::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?Combat {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>