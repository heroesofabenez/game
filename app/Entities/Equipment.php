<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for equipment
 * 
 * @author Jakub Konečný
 * @property-read array $deployParams Deploy params of the equipment
 * @property bool $worn Is the item worn?
 */
class Equipment extends BaseEntity {
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $description;
  /** @var string */
  protected $slot;
  /** @var string */
  protected $type;
  /** @var int */
  protected $required_lvl;
  /** @var int */
  protected $required_class;
  /** @var int */
  protected $price;
  /** @var int */
  protected $strength;
  /** @var int */
  protected $durability;
  /** @var bool */
  protected $worn = false;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->getName() != "equipment") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
  
  /**
   * @return bool
   */
  function getWorn(): bool {
    return $this->worn;
  }
  
  /**
   * @param bool $value
   */
  function setWorn(bool $value) {
    $this->worn = $value;
  }
  
  /**
   * @param int $value
   */
  function setRequired_class(int $value) {
    $this->required_class = $value;
  }
  
  /**
   * Returns deploy parameters of the equipment (for effect to character)
   * 
   * @return array params
   */
  function getDeployParams(): array {
    $stat = [
      "weapon" => "damage", "armor" => "defense", "shield" => "dodge", "amulet" => "initiative"
    ];
    $return = [
      "id" => "equipment" . $this->id . "bonusEffect",
      "type" => "buff",
      "stat" => $stat[$this->slot],
      "value" => $this->strength,
      "source" => "equipment",
      "duration" => "combat"
    ];
    return $return;
  }
}
?>