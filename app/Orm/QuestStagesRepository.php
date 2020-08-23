<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * QuestStagesRepository
 *
 * @author Jakub Konečný
 * @method QuestStage|null getById(int $id)
 * @method QuestStage|null getBy(array $conds)
 * @method ICollection|QuestStage[] findBy(array $conds)
 * @method ICollection|QuestStage[] findAll()
 */
final class QuestStagesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [QuestStage::class];
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
      "requiredClass" => $class
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