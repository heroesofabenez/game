<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharactersRepository
 *
 * @author Jakub Konečný
 */
final class CharactersRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Character::class];
  }
  
  /**
   * @param int $id
   * @return Character|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
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