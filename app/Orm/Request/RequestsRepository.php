<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * RequstsRepository
 *
 * @author Jakub Konečný
 */
class RequestsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Request::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?Request {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $guildLeader
   * @return ICollection|Request[]
   */
  public function findUnresolvedGuildApplications($guildLeader): ICollection {
    return $this->findBy([
      "to" => $guildLeader,
      "type" => Request::TYPE_GUILD_APP,
      "status" => Request::STATUS_NEW,
    ]);
  }
}
?>