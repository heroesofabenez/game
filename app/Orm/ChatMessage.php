<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ChatMessage
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $message
 * @property Character $character {m:1 Character::$chatMessages}
 * @property QuestArea|null $area {m:1 QuestArea::$chatMessages} {default null}
 * @property QuestStage|null $stage {m:1 QuestStage::$chatMessages} {default null}
 * @property Guild|null $guild {m:1 Guild::$chatMessages} {default null}
 * @property \DateTimeImmutable $when
 * @property string $whenS {virtual}
 */
final class ChatMessage extends \Nextras\Orm\Entity\Entity {
  protected function getterWhenS(): string {
    return $this->when->format("Y-m-d H:i:s");
  }
  
  public function onBeforeInsert(): void {
    $this->when = new \DateTimeImmutable();
  }
}
?>