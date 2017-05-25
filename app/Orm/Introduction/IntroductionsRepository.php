<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * IntroductionsRepository
 *
 * @author Jakub Konečný
 */
class IntroductionsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [Introduction::class];
  }
  
  /**
   * @param int $id
   * @return Introduction|NULL
   */
  function getById($id): ?Introduction {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>