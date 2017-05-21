<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildRanksRepository
 *
 * @author Jakub Konečný
 */
class GuildRanksRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [GuildRank::class];
  }
  
  /**
   * @param int $id
   * @return GuildRank|NULL
   */
  function getById($id): ?GuildRank {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>