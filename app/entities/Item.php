<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for item
 * 
 * @author Jakub Konečný
 */
class Item extends \Nette\Object {
  /** @var int */
  public $id;
  /** @var string */
  public $name;
  /** @var string */
  public $description;
  /** @var string */
  public $image;
  /** @var int */
  public $price;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->name != "items") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>