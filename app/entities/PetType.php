<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for pet type
 *
 * @property-read string $bonusStat
 * @property-read int $bonusValue
 * @author Jakub Konečný
 */
class PetType extends BaseEntity {
  protected $id;
  protected $name;
  protected $bonus_stat;
  protected $bonus_value;
  protected $image;
  protected $required_level;
  protected $required_class;
  protected $required_race;
  protected $cost;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->name != "pet_types") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
  
  function getBonusStat() {
    return $this->bonus_stat;
  }
  
  function getBonusValue() {
    return $this->bonus_value;
  }
  
  function __toString() {
    return $this->name;
  }
}
?>