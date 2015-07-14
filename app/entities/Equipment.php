<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for equipment
 * 
 * @author Jakub Konečný
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
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->name != "equipment") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>