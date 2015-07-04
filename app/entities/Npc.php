<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for npc
 * 
 * @author Jakub Konečný
 */
class NPC extends \Nette\Object {
  /** @var int id */
  public $id;
  /** @var string name */
  public $name;
  /** @var string descrption */
  public $description;
  /** @var int id of race */
  public $race;
  /** @var string type of npc */
  public $type;
  /** @var string */
  public $sprite;
  /** @var string */
  public $portrait;
  /** @var int id of stage */
  public $stage;
  /** @var int */
  public $pos_x;
  /** @var int */
  public $pos_y;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->name != "npcs") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>