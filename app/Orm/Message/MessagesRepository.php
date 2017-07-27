<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * MessagesRepository
 *
 * @author Jakub Konečný
 */
class MessagesRepository extends \Nextras\Orm\Repository\Repository {
  static function getEntityClassNames(): array {
    return [Message::class];
  }
  
  /**
   * @param int $id
   */
  function getById($id): ?Message {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $user
   * @return ICollection|Message[]
   */
  function findByFrom($user): ICollection {
    return $this->findBy([
      "from" => $user
    ]);
  }
  
  /**
   * @param Character|int $user
   * @return ICollection|Message[]
   */
  function findByTo($user): ICollection {
    return $this->findBy([
      "to" => $user
    ]);
  }
}
?>