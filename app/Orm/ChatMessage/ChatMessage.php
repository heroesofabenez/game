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
 * @property QuestArea|NULL $area {m:1 QuestArea::$chatMessages} {default NULL}
 * @property QuestStage|NULL $stage {m:1 QuestStage::$chatMessages} {default NULL}
 * @property Guild|NULL $guild {m:1 Guild::$chatMessages} {default NULL}
 * @property \DateTime $when
 * @property string $whenS
 */
class ChatMessage extends \Nextras\Orm\Entity\Entity {
  protected function getterWhenS(): string {
    return $this->when->format("Y-m-d H:i:s");
  }
  
  protected function onBeforeInsert() {
    $this->when = time();
  }
}
?>