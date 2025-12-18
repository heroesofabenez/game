<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * PveArenaOpponentEquipment
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property PveArenaOpponent $npc {m:1 PveArenaOpponent::$equipment}
 * @property Item $item {m:1 Item, oneSided=true}
 */
final class PveArenaOpponentEquipment extends \Nextras\Orm\Entity\Entity
{
}
