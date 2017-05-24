<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ChatMessagesRepository
 *
 * @author Jakub Konečný
 */
class ChatMessagesRepository extends \Nextras\Orm\Repository\Repository {
  /**
   * @return string[]
   */
  static function getEntityClassNames(): array {
    return [ChatMessage::class];
  }
  
  /**
   * @param int $id
   * @return ChatMessage|NULL
   */
  function getById($id): ?ChatMessage {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>