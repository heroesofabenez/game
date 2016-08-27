<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for stage
 * 
 * @author Jakub Konečný
 */
class Stage extends BaseEntity {
  /** @var int id */
  protected $id;
  /** @var string name */
  protected $name;
  /** @var string description */
  protected $description;
  /** @var int minimum level to enter stage */
  protected $required_level;
  /** @var int id of race needed to enter stage */
  protected $required_race;
  /** @var int id of class needed to enter stage */
  protected $required_occupation;
  /** @var int id of parent area */
  protected $area;
  /** @var int */
  protected $pos_x;
  /** @var int */
  protected $pos_y;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->getName() != "quest_stages") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>