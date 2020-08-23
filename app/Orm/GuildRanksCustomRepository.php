<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * GuildRanksCustomRepository
 *
 * @author Jakub Konečný
 * @method GuildRankCustom|null getById(int $id)
 * @method GuildRankCustom|null getBy(array $conds)
 * @method ICollection|GuildRankCustom[] findBy(array $conds)
 * @method ICollection|GuildRankCustom[] findAll()
 */
final class GuildRanksCustomRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [GuildRankCustom::class];
  }
  
  /**
   * @param Guild|int $guild
   * @param GuildRank|int $rank
   */
  public function getByGuildAndRank($guild, $rank): ?GuildRankCustom {
    return $this->getBy([
      "guild" => $guild, "rank" => $rank
    ]);
  }
  
  /**
   * @param Guild|int $guild
   * @return ICollection|GuildRankCustom[]
   */
  public function findByGuild($guild): ICollection {
    return $this->findBy([
      "guild" => $guild
    ]);
  }
}
?>