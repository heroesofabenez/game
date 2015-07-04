<?php
namespace HeroesofAbenez;

/**
 * Data structure for equipment
 * 
 * @author Jakub Konečný
 */
class Equipment extends \Nette\Object {
  /** @var int */
  public $id;
  /** @var string */
  public $name;
  /** @var string */
  public $description;
  /** @var string */
  public $slot;
  /** @var string */
  public $type;
  /** @var int */
  public $required_lvl;
  /** @var int */
  public $required_class;
  /** @var int */
  public $price;
  /** @var int */
  public $strength;
  /** @var int */
  public $durability;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->name != "equipment") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>