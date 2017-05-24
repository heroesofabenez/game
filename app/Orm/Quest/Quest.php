<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * Quest
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $introduction
 * @property string $endText
 * @property int $costMoney {default 0}
 * @property Item|NULL $neededItem {m:1 Item::$neededForQuests}
 * @property Quest|NULL $neededQuest {m:1 Quest::$children}
 * @property OneHasMany|Quest[] $children {1:m Quest::$neededQuest}
 * @property int|NULL $neededLevel
 * @property int $itemAmount {default 1}
 * @property bool $itemLose {default true}
 * @property int $rewardMoney
 * @property int $rewardXp
 * @property Item|NULL $rewardItem {m:1 Item::$rewardedForQuests}
 * @property Npc $npcStart {m:1 Npc::$startQuests}
 * @property Npc $npcEnd {m:1 Npc::$endQuests}
 * @property int $order
 * @property OneHasMany|CharacterQuest[] $characterQuests {1:m CharacterQuest::$quest}
 */
class Quest extends \Nextras\Orm\Entity\Entity {
  
}
?>