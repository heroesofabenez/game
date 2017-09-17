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
  public const TYPE_GUILD_JOIN = "guild_join";
  public const TYPE_GUILD_APP = "guild_app";
  public const TYPE_GROUP_JOIN = "group_join";
  public const TYPE_FRIENDSHIP = "friendship";
  public const STATUS_NEW = "new";
  public const STATUS_ACCEPTED = "accepted";
  public const STATUS_DECLINED = "declined";
  
  protected function getterSentAt(): string {
    return $this->sent->format("Y-m-d H:i:s");
  }
  
  protected function onBeforeInsert() {
    $this->sent = new \DateTime;
  }
}
?>