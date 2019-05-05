<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CombatsRepository
 *
 * @author Jakub Konečný
 */
final class CombatsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Combat::class];
  }
  
  /**
   * @param int $id
   * @return Combat|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>