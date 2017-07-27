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
   */
  public function getById($id): ?QuestStage {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param QuestArea|int $area
   * @return ICollection|QuestStage[]
   */
  public function findByArea($area): ICollection {
    return $this->findBy([
      "area" => $area
    ]);
  }
  
  /**
   * @param CharacterClass|int $class
   */
  public function getClassStartingLocation($class): ?QuestStage {
    return $this->getBy([
      "requiredLevel" => 0,
      "requiredOccupation" => $class
    ]);
  }
  
  /**
   * @param CharacterRace|int $race
   */
  public function getRaceStartingLocation($race): ?QuestStage {
    return $this->getBy([
      "requiredLevel" => 0,
      "requiredRace" => $race
    ]);
  }
}
?>