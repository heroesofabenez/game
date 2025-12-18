<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * RoutesStage
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property QuestStage $from {m:1 QuestStage::$routesOutgoing}
 * @property QuestStage $to {m:1 QuestStage::$routesIncoming}
 */
final class RoutesStage extends \Nextras\Orm\Entity\Entity
{
}
