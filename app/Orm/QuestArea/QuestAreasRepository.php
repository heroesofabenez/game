<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * QuestAreasRepository
 *
 * @author Jakub Konečný
 */
final class QuestAreasRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [QuestArea::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?QuestArea {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>