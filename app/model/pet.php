<?php
namespace HeroesofAbenez;

/**
 * Structure for pet
 * 
 * @property-read array deployParams 
 * @author Jakub Konečný
 */
class Pet extends \Nette\Object {
  /** @var int Pet's id */
  protected $id;
  /** @var string Pet's type */
  protected $type;
  /** @var string Pet's name */
  protected $name;
  /** @var string To which stat the pet provides bonus */
  protected $bonus_stat;
  /** @var int Size of provided bonus */
  protected $bonus_value;
  
  /**
   * @param int $id
   * @param string $type
   * @param string $name
   * @param string $bonus_stat
   * @param int $bonus_value
   */
  function __construct($id, $type, $name, $bonus_stat, $bonus_value) {
    $this->id = $id;
    $this->type = $type;
    $this->name = $name;
    $this->bonus_stat = $bonus_stat;
    $this->bonus_value = $bonus_value;
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
      "stat" => $this->bonus_stat,
      "value" => $this->bonus_value,
      "source" => "pet",
      "duration" => "combat"
    );
  }
}
?>