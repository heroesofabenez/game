<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * ArenaFightsCountRepository
 *
 * @author Jakub Konečný
 * @method ArenaFightCount|null getById(int $id)
 * @method ArenaFightCount|null getBy(array $conds)
 * @method ICollection|ArenaFightCount[] findBy(array $conds)
 * @method ICollection|ArenaFightCount[] findAll()
 */
final class ArenaFightsCountRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [ArenaFightCount::class];
  }

  public function getByCharacterAndDay(Character|int $character, string $day): ?ArenaFightCount {
    return $this->getBy([
      "character" => $character,
      "day" => $day
    ]);
  }
}
?>