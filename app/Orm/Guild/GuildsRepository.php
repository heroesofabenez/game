<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildsRepository
 *
 * @author Jakub Konečný
 */
final class GuildsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Guild::class];
  }
  
  /**
   * @param int $id
   * @return Guild|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
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