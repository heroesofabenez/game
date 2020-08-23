<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * NpcsRepository
 *
 * @author Jakub Konečný
 * @method Npc|null getById(int $id)
 * @method Npc|null getBy(array $conds)
 * @method ICollection|Npc[] findBy(array $conds)
 * @method ICollection|Npc[] findAll()
 */
final class NpcsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Npc::class];
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