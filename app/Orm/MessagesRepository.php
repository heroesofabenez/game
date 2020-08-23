<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Collection\ICollection;

/**
 * MessagesRepository
 *
 * @author Jakub Konečný
 * @method Message|null getById(int $id)
 * @method Message|null getBy(array $conds)
 * @method ICollection|Message[] findBy(array $conds)
 * @method ICollection|Message[] findAll()
 */
final class MessagesRepository extends \Nextras\Orm\Repository\Repository {
  public static function getEntityClassNames(): array {
    return [Message::class];
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