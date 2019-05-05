<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * MessagesRepository
 *
 * @author Jakub Konečný
 */
final class MessagesRepository extends \Nextras\Orm\Repository\Repository {
  public static function getEntityClassNames(): array {
    return [Message::class];
  }
  
  /**
   * @param int $id
   * @return Message|null
   */
  public function getById($id): ?\Nextras\Orm\Entity\IEntity {
    return $this->getBy([
      "id" => $id
    ]);
  }
  
  /**
   * @param Character|int $user
   * @return ICollection|Message[]
   */
  public function findByFrom($user): ICollection {
    return $this->findBy([
      "from" => $user
    ]);
  }
  
  /**
   * @param Character|int $user
   * @return ICollection|Message[]
   */
  public function findByTo($user): ICollection {
    return $this->findBy([
      "to" => $user
    ]);
  }
}
?>