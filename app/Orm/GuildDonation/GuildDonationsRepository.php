<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildDonationsRepository
 *
 * @author Jakub Konečný
 */
final class GuildDonationsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [GuildDonation::class];
  }
  
  /**
   * @param int $id
   * @return GuildDonation|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>