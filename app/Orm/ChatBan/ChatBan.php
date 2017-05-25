<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ChatBan
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$chatBans}
 * @property \DateTime $since
 * @property \DateTime $till
 * @property string $reason
 * @property \DateTime|NULL $revoken {default NULL}
 */
class ChatBan extends \Nextras\Orm\Entity\Entity {
  protected function onBeforeInsert() {
    $this->since = new \DateTime;
  }
}
?>