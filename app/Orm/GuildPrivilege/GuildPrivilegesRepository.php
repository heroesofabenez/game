<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildPrivilegesRepository
 *
 * @author Jakub Konečný
 */
final class GuildPrivilegesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [GuildPrivilege::class];
  }
  
  /**
   * @param int $id
   * @return GuildPrivilege|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>