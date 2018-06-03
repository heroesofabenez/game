<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildRanksRepository
 *
 * @author Jakub Konečný
 */
final class GuildRanksRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [GuildRank::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?GuildRank {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>