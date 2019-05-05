<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * QuestsRepository
 *
 * @author Jakub Konečný
 */
final class QuestsRepository extends \Nextras\Orm\Repository\Repository {
  public static function getEntityClassNames(): array {
    return [Quest::class];
  }
  
  /**
   * @param int $id
   * @return Quest|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>