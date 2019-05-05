<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ChatMessagesRepository
 *
 * @author Jakub Konečný
 */
final class ChatMessagesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  public static function getEntityClassNames(): array {
    return [ChatMessage::class];
  }
  
  /**
   * @param int $id
   * @return ChatMessage|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>