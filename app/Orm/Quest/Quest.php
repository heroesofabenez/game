<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;
use Nexendrie\Utils\Numbers;

/**
 * Quest
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property int $costMoney {default 0}
 * @property Item|null $neededItem {m:1 Item::$neededForQuests}
 * @property Quest|null $neededQuest {m:1 Quest::$children}
 * @property OneHasMany|Quest[] $children {1:m Quest::$neededQuest}
 * @property int $neededLevel {default 1}
 * @property int $itemAmount {default 1}
 * @property bool $itemLose {default true}
 * @property int $rewardMoney
 * @property int $rewardXp
 * @property int $rewardWhiteKarma {default 0}
 * @property int $rewardDarkKarma {default 0}
 * @property Item|null $rewardItem {m:1 Item::$rewardedForQuests}
 * @property PetType|null $rewardPet {m:1 PetType::$rewardedForQuests}
 * @property Npc $npcStart {m:1 Npc, oneSided=true}
 * @property Npc $npcEnd {m:1 Npc, oneSided=true}
 * @property bool $progress {virtual}
 */
final class Quest extends \Nextras\Orm\Entity\Entity {
  public function setterRewardWhiteKarma(int $value): int {
    return $this->rewardWhiteKarma = Numbers::range($value, 0, 99);
  }

  public function setterRewardDarkKarma(int $value): int {
    return $this->rewardDarkKarma = Numbers::range($value, 0, 99);
  }
}
?>