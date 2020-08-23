<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * PetTypesRepository
 *
 * @author Jakub Konečný
 * @method PetType|null getById(int $id)
 * @method PetType|null getBy(array $conds)
 * @method ICollection|PetType[] findBy(array $conds)
 * @method ICollection|PetType[] findAll()
 */
final class PetTypesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [PetType::class];
  }
}
?>