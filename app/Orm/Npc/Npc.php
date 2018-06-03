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
 * @property string $karma {enum \HeroesofAbenez\Utils\Karma::KARMA_*}
 * @property int $posX
 * @property int $posY
 * @property OneHasMany|ShopItem[] $items {1:m ShopItem::$npc}
 * @property OneHasMany|Quest[] $startQuests {1:m Quest::$npcStart}
 * @property OneHasMany|Quest $endQuests {1:m Quest::$npcEnd}
 */
final class Npc extends \Nextras\Orm\Entity\Entity {
  public const TYPE_QUEST = "quest";
  public const TYPE_SHOP = "shop";
  public const TYPE_COMMON = "common";
  public const TYPE_ENEMY = "enemy";
}
?>