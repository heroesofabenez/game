<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * GuildsRepository
 *
 * @author Jakub Konečný
 * @method Guild|null getById(int $id)
 * @method Guild|null getBy(array $conds)
 * @method ICollection|Guild[] findBy(array $conds)
 * @method ICollection|Guild[] findAll()
 */
final class GuildsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Guild::class];
  }
  
  public function getByName(string $name): ?Guild {
    return $this->getBy([
      "name" => $name
    ]);
  }
}
?>