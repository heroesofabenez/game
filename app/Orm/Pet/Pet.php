<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Pet
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property PetType $type {m:1 PetType::$pets}
 * @property string|NULL $name
 * @property Character $owner {m:1 Character::$pets}
 * @property bool $deployed {default false}
 */
class Pet extends \Nextras\Orm\Entity\Entity {
  
}
?>