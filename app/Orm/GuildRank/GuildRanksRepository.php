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
   * @return GuildRank|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>