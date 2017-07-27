<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildsRepository
 *
 * @author Jakub Konečný
 */
class GuildsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [Guild::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?Guild {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  public function getByName(string $name): ?Guild {
    return $this->getBy([
      "name" => $name
    ]);
  }
}
?>