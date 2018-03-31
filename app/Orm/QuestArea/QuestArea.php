<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany,
    Nexendrie\Utils\Numbers;

/**
 * QuestArea
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $description
 * @property int|NULL $requiredLevel {default 0}
 * @property CharacterRace|NULL $requiredRace {m:1 CharacterRace::$areas}
 * @property CharacterClass|NULL $requiredOccupation {m:1 CharacterClass::$areas}
 * @property int|NULL $posX
 * @property int|NULL $posY
 * @property OneHasMany|QuestStage[] $stages {1:m QuestStage::$area}
 * @property OneHasMany|RoutesArea[] $routesOutgoing {1:m RoutesArea::$from}
 * @property OneHasMany|RoutesArea[] $routesIncoming {1:m RoutesArea::$to}
 * @property OneHasMany|ChatMessage[] $chatMessages {1:m ChatMessage::$area}
 */
class QuestArea extends \Nextras\Orm\Entity\Entity {
  protected function setterRequiredLevel(int $value): int {
    return Numbers::range($value, 0, 99);
  }
}
?>