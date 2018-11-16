<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nexendrie\Utils\Numbers;

/**
 * CharacterQuest
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$quests}
 * @property Quest $quest {m:1 Quest, oneSided=true}
 * @property int $progress {default static::PROGRESS_STARTED}
 */
final class CharacterQuest extends \Nextras\Orm\Entity\Entity {
  public const PROGRESS_OFFERED = 0;
  public const PROGRESS_STARTED = 1;
  public const PROGRESS_FINISHED = 3;

  protected function setterProgress(int $value): int {
    return Numbers::range($value, static::PROGRESS_OFFERED, static::PROGRESS_FINISHED);
  }
}
?>