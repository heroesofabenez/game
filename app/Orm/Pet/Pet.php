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
 * @property-read string $bonusStat {virtual}
 * @property-read int $bonusValue {virtual}
 * @property-read array $deployParams {virtual}
 */
class Pet extends \Nextras\Orm\Entity\Entity {
  /**
   * @return string
   */
  protected function getterBonusStat(): string {
    return $this->type->bonusStat;
  }
  
  /**
   * @return int
   */
  protected function getterBonusValue(): int {
    return $this->type->bonusValue;
  }
  
  /**
   * @return array
   */
  protected function getterDeployParams(): array {
    $stats = [
      "str" => "strength", "dex" => "dexterity", "con" => "constitution", "int" => "intelligence", "char" => "charisma"
    ];
    return [
      "id" => "pet" . $this->id . "bonusEffect",
      "type" => "buff",
      "stat" => str_replace(array_keys($stats), array_values($stats), $this->bonusStat),
      "value" => $this->bonusValue,
      "source" => "pet",
      "duration" => "combat"
    ];
  }
}
?>