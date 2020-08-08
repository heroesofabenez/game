<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * ArenaFightCount
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$arenaFights}
 * @property string $day
 * @property int $amount {default 1}
 */
final class ArenaFightCount extends \Nextras\Orm\Entity\Entity {
  
}
?>