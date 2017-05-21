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
 * @property string $description
 * @property int|NULL $requiredLevel {default 0}
 * @property CharacterRace|NULL $requiredRace {m:1 CharacterRace::$stages}
 * @property CharacterClass|NULL $requiredOccupation {m:1 CharacterClass::$stages}
 * @property QuestArea $area {m:1 QuestArea::$stages}
 * @property int|NULL $posX
 * @property int|NULL $posY
 * @property OneHasMany|RoutesStage[] $routesOutgoing {1:m RoutesStage::$from}
 * @property OneHasMany|RoutesStage[] $routesIncoming {1:m RoutesStage::$to}
 */
class QuestStage extends \Nextras\Orm\Entity\Entity {
  
}
?>