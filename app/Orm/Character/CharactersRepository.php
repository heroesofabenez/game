<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharactersRepository
 *
 * @author Jakub Konečný
 */
class CharactersRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [Character::class];
  }
  
  /**
   * @param int $id
   */
  function getById($id): ?Character {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  function getByName(string $name): ?Character {
    return $this->getBy([
      "name" => $name
    ]);
  }
  
  function getByOwner(int $owner): ?Character {
    return $this->getBy([
      "owner" => $owner
    ]);
  }
  
  /**
   * @param Guild|int $guild
   * @return ICollection|Character[]
   */
  function findByGuild($guild): ICollection {
    return $this->findBy([
      "guild" => $guild
    ])->orderBy("guildrank", ICollection::DESC)
      ->orderBy("id");
  }
}
?>