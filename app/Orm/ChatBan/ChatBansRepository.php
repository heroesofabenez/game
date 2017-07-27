<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ChatBansRepository
 *
 * @author Jakub Konečný
 */
class ChatBansRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [ChatBan::class];
  }
  
  /**
   * @param int $id
   */
  function getById($id): ?ChatBan {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>