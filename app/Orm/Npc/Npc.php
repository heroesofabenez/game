<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;
use Nexendrie\Utils\Numbers;

/**
 * Npc
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property CharacterRace $race {m:1 CharacterRace::$npcs}
 * @property bool $quests {default false}
 * @property bool $shop {default false}
 * @property bool $fight {default false}
 * @property string $sprite
 * @property string $portrait
 * @property QuestStage $stage {m:1 QuestStage::$npcs}
 * @property string $karma {enum \HeroesofAbenez\Utils\Karma::KARMA_*}
 * @property string $personality {enum static::PERSONALITY_*}
 * @property int $level {default 1}
 * @property int $posX
 * @property int $posY
 * @property OneHasMany|ShopItem[] $items {1:m ShopItem::$npc, orderBy=order}
 */
final class Npc extends \Nextras\Orm\Entity\Entity {
  public const PERSONALITY_FRIENDLY = "friendly";
  public const PERSONALITY_CRAZY = "crazy";
  public const PERSONALITY_SHY = "shy";
  public const PERSONALITY_HOSTILE = "hostile";
  public const PERSONALITY_RESERVED = "reserved";
  public const PERSONALITY_ELITIST = "elitist";
  public const PERSONALITY_TEACHING = "teaching";
  public const PERSONALITY_RACIST = "racist";
  public const PERSONALITY_MISOGYNIST = "misogynist";

  protected function setterLevel(int $value): int {
    return Numbers::range($value, 1, 999);
  }
}
?>