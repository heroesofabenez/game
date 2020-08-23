<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharactersRepository
 *
 * @author Jakub Konečný
 * @method Character|null getById(int $id)
 * @method Character|null getBy(array $conds)
 * @method ICollection|Character[] findBy(array $conds)
 * @method ICollection|Character[] findAll()
 */
final class CharactersRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Character::class];
  }
  
  public function getByName(string $name): ?Character {
    return $this->getBy([
      "name" => $name
    ]);
  }
  
  public function getByOwner(int $owner): ?Character {
    return $this->getBy([
      "owner" => $owner
    ]);
  }
  
  /**
   * @param Guild|int $guild
   * @return ICollection|Character[]
   */
  public function findByGuild($guild): ICollection {
    return $this->findBy([
      "guild" => $guild
    ])->orderBy("guildrank", ICollection::DESC)
      ->orderBy("id");
  }
}
?>