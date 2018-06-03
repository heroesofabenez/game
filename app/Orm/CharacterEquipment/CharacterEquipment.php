<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterEquipment
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$equipment}
 * @property Equipment $item {m:1 Equipment::$characterEquipment}
 * @property int $amount {default 1}
 * @property bool $worn {default 0}
 */
final class CharacterEquipment extends \Nextras\Orm\Entity\Entity {
  
}
?>