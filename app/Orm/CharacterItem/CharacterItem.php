<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterItem
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$items}
 * @property Item $item {m:1 Item::$characterItems}
 * @property int $amount {default 1}
 */
class CharacterItem extends \Nextras\Orm\Entity\Entity {
  
}
?>