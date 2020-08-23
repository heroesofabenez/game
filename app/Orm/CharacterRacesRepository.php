<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * CharacterRacesRepository
 *
 * @author Jakub Konečný
 * @method CharacterRace|null getById(int $id)
 * @method CharacterRace|null getBy(array $conds)
 * @method ICollection|CharacterRace[] findBy(array $conds)
 * @method ICollection|CharacterRace[] findAll()
 */
final class CharacterRacesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [CharacterRace::class];
  }
}
?>