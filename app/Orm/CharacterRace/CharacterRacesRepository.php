<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterRacesRepository
 *
 * @author Jakub Konečný
 */
final class CharacterRacesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [CharacterRace::class];
  }
  
  /**
   * @param int $id
   * @return CharacterRace|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>