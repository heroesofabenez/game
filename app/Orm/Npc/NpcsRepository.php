<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * NpcsRepository
 *
 * @author Jakub Konečný
 */
final class NpcsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Npc::class];
  }
  
  /**
   * @param int $id
   * @return Npc|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param QuestStage|int $stage
   * @return ICollection|Npc[]
   */
  public function findByStage($stage): ICollection {
    return $this->findBy([
      "stage" => $stage
    ]);
  }
}
?>