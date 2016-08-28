<?php
namespace HeroesofAbenez\Entities;

/**
 * Structure for pet
 * 
 * @property-read int $typeId
 * @property-read string $bonusStat To which stat the pet provides bonus
 * @property-read int $bonusValue Size of provided bonus
 * @property-read array $deployParams
 * @author Jakub Konečný
 */
class Pet extends BaseEntity {
  /** @var int Pet's id */
  protected $id;
  /** @var PetType Pet's type */
  protected $type;
  /** @var string Pet's name */
  protected $name;
  /** @var bool Is the pet deployed? */
  protected $deployed;
  
  /**
   * @param int $id
   * @param PetType $type
   * @param string $name
   * @param bool $deployed
   */
  function __construct($id, PetType $type, $name, $deployed = false) {
    $this->id = $id;
    $this->type = $type;
    $this->name = $name;
    $this->deployed = (bool) $deployed;
  }
  
  /**
   * @return string
   */
  function getBonusStat() {
    return $this->type->bonusStat;
  }
  
  /**
   * @return int
   */
  function getBonusValue() {
    return $this->type->bonusValue;
  }
  
  /**
   * @return int
   */
  function getTypeId() {
    return $this->type->id;
  }
  
  /**
   * Returns deploy parameters of the pet (for effect to character)
   * 
   * @return array params
   */
  function getDeployParams() {
    $statTransform = [
      "str" => "strength", "dex" => "dexterity", "con" => "constitution", "int" => "intelligence", "char" => "charisma"
    ];
    return [
      "id" => "pet" . $this->id . "bonusEffect",
      "type" => "buff",
      "stat" => str_replace(array_keys($statTransform), array_values($statTransform), $this->getBonusStat()),
      "value" => $this->getBonusValue(),
      "source" => "pet",
      "duration" => "combat"
    ];
  }
}
?>