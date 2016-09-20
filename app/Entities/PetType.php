<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Data structure for pet type
 *
 * @property-read string $bonusStat
 * @property-read int $bonusValue
 * @author Jakub Konečný
 */
class PetType extends BaseEntity {
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $bonus_stat;
  /** @var int */
  protected $bonus_value;
  /** @var string */
  protected $image;
  /** @var int */
  protected $required_level;
  /** @var int */
  protected $required_class;
  /** @var int */
  protected $required_race;
  /** @var int */
  protected $cost;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->getName() != "pet_types") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
  
  /**
   * @return string
   */
  function getBonusStat(): string {
    return $this->bonus_stat;
  }
  
  /**
   * @return int
   */
  function getBonusValue(): int {
    return $this->bonus_value;
  }
  
  /**
   * @return string
   */
  function __toString(): string {
    return $this->name;
  }
}
?>