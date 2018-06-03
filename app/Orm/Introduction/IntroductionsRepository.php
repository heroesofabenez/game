<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * IntroductionsRepository
 *
 * @author Jakub Konečný
 */
final class IntroductionsRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [Introduction::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?Introduction {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>