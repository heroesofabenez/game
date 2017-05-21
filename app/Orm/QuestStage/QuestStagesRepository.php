<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * QuestStagesRepository
 *
 * @author Jakub Konečný
 */
class QuestStagesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [QuestStage::class];
  }
  
  /**
   * @param int $id
   * @return QuestStage|NULL
   */
  function getById($id): ?QuestStage {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param QuestArea|int $area
   * @return ICollection|QuestStage[]
   */
  function findByArea($area): ICollection {
    return $this->findBy([
      "area" => $area
    ]);
  }
}
?>