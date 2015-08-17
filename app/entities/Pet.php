<?php
namespace HeroesofAbenez\Entities;

/**
 * Structure for pet
 * 
 * @property-read array $deployParams
 * @author Jakub Konečný
 */
class Pet extends BaseEntity {
  /** @var int Pet's id */
  protected $id;
  /** @var string Pet's type */
  protected $type;
  /** @var string Pet's name */
  protected $name;
  /** @var string To which stat the pet provides bonus */
  protected $bonusStat;
  /** @var int Size of provided bonus */
  protected $bonusValue;
  /** @var bool Is the pet deployed? */
  protected $deployed;
  
  /**
   * @param int $id
   * @param string $type
   * @param string $name
   * @param string $bonus_stat
   * @param int $bonus_value
   * @param bool $deployed
   */
  function __construct($id, $type, $name, $bonus_stat, $bonus_value, $deployed = false) {
    $this->id = $id;
    $this->type = $type;
    $this->name = $name;
    $this->bonusStat = $bonus_stat;
    $this->bonusValue = $bonus_value;
    $this->deployed = (bool) $deployed;
  }
  
  /**
   * Returns deploy parameters of the pet (for effect to character)
   * 
   * @return array params
   */
  function getDeployParams() {
    return array(
      "id" => "pet" . $this->id . "bonusEffect",
      "type" => "buff",
      "stat" => $this->bonusStat,
      "value" => $this->bonusValue,
      "source" => "pet",
      "duration" => "combat"
    );
  }
}
?>