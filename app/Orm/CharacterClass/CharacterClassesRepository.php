<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterClassesRepository
 *
 * @author Jakub Konečný
 */
final class CharacterClassesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [CharacterClass::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?CharacterClass {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>