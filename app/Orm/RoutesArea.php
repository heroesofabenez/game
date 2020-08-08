<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * RoutesArea
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property QuestArea $from {m:1 QuestArea::$routesOutgoing}
 * @property QuestArea $to {m:1 QuestArea::$routesIncoming}
 */
final class RoutesArea extends \Nextras\Orm\Entity\Entity {
  
}
?>