<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for area
 * 
 * @author Jakub Konečný
 */
class Area extends BaseEntity {
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
  /** @var int */
  protected $pos_x;
  /** @var int */
  protected $pos_y;
  
  /**
   * @param int $id
   * @param string $name
   * @param string $description
   * @param int $required_level
   * @param int $required_race
   * @param int $required_occupation
   */
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->name != "quest_areas") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>