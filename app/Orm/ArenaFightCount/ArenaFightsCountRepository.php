<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ArenaFightsCountRepository
 *
 * @author Jakub Konečný
 */
class ArenaFightsCountRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [ArenaFightCount::class];
  }
  
  /**
   * @param int $id
   * @return ArenaFightCount|NULL
   */
  function getById($id): ?ArenaFightCount {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $character
   * @param string $day
   * @return ArenaFightCount|NULL
   */
  function getByCharacterAndDay($character, string $day): ?ArenaFightCount {
    return $this->getBy([
      "character" => $character,
      "day" => $day
    ]);
  }
}
?>