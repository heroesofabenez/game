<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ChatBan
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$chatBans}
 * @property \DateTimeImmutable $since
 * @property \DateTimeImmutable $till
 * @property string $reason
 * @property \DateTimeImmutable|null $revoken {default null}
 */
final class ChatBan extends \Nextras\Orm\Entity\Entity {
  public function onBeforeInsert() {
    $this->since = new \DateTimeImmutable();
  }
}
?>