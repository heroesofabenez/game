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
   * @return ChatBan|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>