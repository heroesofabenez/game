<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * GuildRanksCustomRepository
 *
 * @author Jakub Konečný
 */
class GuildRanksCustomRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [GuildRankCustom::class];
  }
  
  /**
   * @param int $id
   * @return GuildRankCustom|NULL
   */
  function getById($id): ?GuildRankCustom {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Guild|int $guild
   * @param GuildRank|int $rank
   * @return GuildRankCustom|NULL
   */
  function getByGuildAndRank($guild, $rank): ?GuildRankCustom {
    return $this->getBy([
      "guild" => $guild, "rank" => $rank
    ]);
  }
  
  /**
   * @param Guild|int $guild
   * @return ICollection|GuildRankCustom[]
   */
  function findByGuild($guild): ICollection {
    return $this->findBy([
      "guild" => $guild
    ]);
  }
}
?>