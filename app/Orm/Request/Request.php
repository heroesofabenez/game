<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Request
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $from {m:1 Character::$sentRequests}
 * @property Character $to {m:1 Character::$receivedRequests}
 * @property string $type {enum self::TYPE_*}
 * @property \DateTime $sent
 * @property-read string $sentAt {virtual}
 * @property string $status {enum self::STATUS_*} {default self::STATUS_NEW}
 */
class Request extends \Nextras\Orm\Entity\Entity {
  const TYPE_GUILD_JOIN = "guild_join";
  const TYPE_GUILD_APP = "guild_app";
  const TYPE_GROUP_JOIN = "group_join";
  const TYPE_FRIENDSHIP = "friendship";
  const STATUS_NEW = "new";
  const STATUS_ACCEPTED = "accepted";
  const STATUS_DECLINED = "declined";
  
  protected function getterSentAt() {
    return $this->sent->format("Y-m-d H:i:s");
  }
  
  protected function onBeforeInsert() {
    $this->sent = new \DateTime;
  }
}
?>