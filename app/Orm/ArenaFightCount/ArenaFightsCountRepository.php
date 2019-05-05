<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ArenaFightsCountRepository
 *
 * @author Jakub Konečný
 */
final class ArenaFightsCountRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [ArenaFightCount::class];
  }
  
  /**
   * @param int $id
   * @return ArenaFightCount|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $character
   */
  public function getByCharacterAndDay($character, string $day): ?ArenaFightCount {
    return $this->getBy([
      "character" => $character,
      "day" => $day
    ]);
  }
}
?>