<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * FriendshipsRepository
 *
 * @author Jakub Konečný
 */
final class FriendshipsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Friendship::class];
  }
  
  /**
   * @param int $id
   * @return Friendship|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }

  /**
   * @param Character|int $character
   * @return ICollection|Friendship[]
   */
  public function findByCharacter($character): ICollection {
    return $this->findBy([
      ICollection::OR,
      "character1" => $character,
      "character2" => $character,
    ]);
  }
}
?>