<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Message
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $from {m:1 Character::$sentMessages}
 * @property Character $to {m:1 Character::$receivedMessages}
 * @property string $subject
 * @property string $text
 * @property \DateTimeImmutable $sent
 * @property-read string $sentAt {virtual}
 * @property bool $read {default false}
 */
class Message extends \Nextras\Orm\Entity\Entity {
  protected function getterSentAt(): string {
    return $this->sent->format("Y-m-d H:i:s");
  }
  
  public function onBeforeInsert() {
    $this->sent = new \DateTimeImmutable;
  }
}
?>