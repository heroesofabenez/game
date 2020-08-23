<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * FriendshipsRepository
 *
 * @author Jakub Konečný
 * @method Friendship|null getById(int $id)
 * @method Friendship|null getBy(array $conds)
 * @method ICollection|Friendship[] findBy(array $conds)
 * @method ICollection|Friendship[] findAll()
 */
final class FriendshipsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Friendship::class];
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