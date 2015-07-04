<?php
namespace HeroesofAbenez;

/**
 * Data structure for area
 * 
 * @author Jakub Konečný
 */
class Area extends \Nette\Object {
  /** @var int id */
  public $id;
  /** @var string name */
  public $name;
  /** @var string description */
  public $description;
  /** @var int minimum level to enter stage */
  public $required_level;
  /** @var int id of race needed to enter stage */
  public $required_race;
  /** @var int id of class needed to enter stage */
  public $required_occupation;
  
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