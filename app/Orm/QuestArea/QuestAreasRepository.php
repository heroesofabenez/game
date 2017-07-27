<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * QuestAreasRepository
 *
 * @author Jakub Konečný
 */
class QuestAreasRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [QuestArea::class];
  }
  
  /**
   * @param int $id
   */
  function getById($id): ?QuestArea {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>