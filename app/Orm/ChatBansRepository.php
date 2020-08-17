<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ChatBansRepository
 *
 * @author Jakub Konečný
 */
final class ChatBansRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [ChatBan::class];
  }
  
  /**
   * @param int $id
   */
  public function getById($id): ?ChatBan {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>