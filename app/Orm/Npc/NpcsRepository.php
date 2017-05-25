<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * NpcsRepository
 *
 * @author Jakub Konečný
 */
class NpcsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [Npc::class];
  }
  
  /**
   * @param int $id
   * @return Npc|NULL
   */
  function getById($id): ?Npc {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param $stage
   * @return ICollection|Npc[]
   */
  function findByStage($stage): ICollection {
    return $this->findBy([
      "stage" => $stage
    ]);
  }
}
?>