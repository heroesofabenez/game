<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for item
 * 
 * @author Jakub Konečný
 */
class Item extends BaseEntity {
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $description;
  /** @var string */
  protected $image;
  /** @var int */
  protected $price;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->getName() != "items") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>