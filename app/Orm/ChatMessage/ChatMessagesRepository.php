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
   */
  public function getById($id): ?ChatMessage {
    return $this->getBy([
      "id" => $id
    ]);
  }
}
?>