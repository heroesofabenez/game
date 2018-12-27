<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * QuestStage
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property int $requiredLevel {default 0}
 * @property CharacterRace|null $requiredRace {m:1 CharacterRace::$stages}
 * @property CharacterClass|null $requiredClass {m:1 CharacterClass::$stages}
 * @property QuestArea $area {m:1 QuestArea::$stages}
 * @property int|null $posX
 * @property int|null $posY
 * @property OneHasMany|RoutesStage[] $routesOutgoing {1:m RoutesStage::$from}
 * @property OneHasMany|RoutesStage[] $routesIncoming {1:m RoutesStage::$to}
 * @property OneHasMany|Npc[] $npcs {1:m Npc::$stage}
 * @property OneHasMany|ChatMessage[] $chatMessages {1:m ChatMessage::$stage}
 * @property OneHasMany|Character[] $characters {1:m Character::$currentStage}
 */
final class QuestStage extends \Nextras\Orm\Entity\Entity {
  
}
?>