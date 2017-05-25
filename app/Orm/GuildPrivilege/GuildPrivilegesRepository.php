<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildPrivilegesRepository
 *
 * @author Jakub Konečný
 */
class GuildPrivilegesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [GuildPrivilege::class];
  }
  
  /**
   * @param int $id
   * @return GuildPrivilege|NULL
   */
  function getById($id): ?GuildPrivilege {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>