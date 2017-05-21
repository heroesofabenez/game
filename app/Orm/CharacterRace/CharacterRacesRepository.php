<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterRacesRepository
 *
 * @author Jakub Konečný
 */
class CharacterRacesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [CharacterRace::class];
  }
  
  /**
   * @param int $id
   * @return CharacterRace|NULL
   */
  function getById($id): ?CharacterRace {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>