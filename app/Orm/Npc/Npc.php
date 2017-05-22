<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * Npc
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string $description
 * @property CharacterRace $race {m:1 CharacterRace::$npcs}
 * @property string $type {enum self::TYPE_*}
 * @property string $sprite
 * @property string $portrait
 * @property QuestStage $stage {m:1 QuestStage::$npcs}
 * @property int $posX
 * @property int $posY
 * @property OneHasMany|ShopItem[] $items {1:m ShopItem::$npc}
 */
class Npc extends \Nextras\Orm\Entity\Entity {
  const TYPE_QUEST = "quest";
  const TYPE_SHOP = "shop";
  const TYPE_COMMON = "common";
  const TYPE_ENEMY = "enemy";
}
?>