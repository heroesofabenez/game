<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterQuest
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$quests}
 * @property Quest $quest {m:1 Quest::$characterQuests}
 * @property int $progress {default 1}
 */
class CharacterQuest extends \Nextras\Orm\Entity\Entity {
  
}
?>