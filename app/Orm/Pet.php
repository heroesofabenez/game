<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Pet
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property PetType $type {m:1 PetType::$pets}
 * @property string|null $name {default null}
 * @property Character $owner {m:1 Character::$pets}
 * @property bool $deployed {default false}
 * @property-read string $bonusStat {virtual}
 * @property-read int $bonusValue {virtual}
 */
final class Pet extends \Nextras\Orm\Entity\Entity {
  protected function getterBonusStat(): string {
    return $this->type->bonusStat;
  }
  
  protected function getterBonusValue(): int {
    return $this->type->bonusValue;
  }
  
  public function toCombatPet(): \HeroesofAbenez\Combat\Pet {
    $data = [];
    $stats = ["id", "deployed", "bonusStat", "bonusValue", ];
    foreach($stats as $stat) {
      $data[$stat] = $this->$stat;
    }
    
    return new \HeroesofAbenez\Combat\Pet($data);
  }
}
?>