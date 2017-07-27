<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterClassesRepository
 *
 * @author Jakub Konečný
 */
class CharacterClassesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [CharacterClass::class];
  }
  
  /**
   * @param int $id
   */
  function getById($id): ?CharacterClass {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>