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
   * @return Introduction|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>