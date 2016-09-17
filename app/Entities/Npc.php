<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for npc
 * 
 * @author Jakub Konečný
 */
class NPC extends BaseEntity {
  /** @var int id */
  protected $id;
  /** @var string name */
  protected $name;
  /** @var string description */
  protected $description;
  /** @var int id of race */
  protected $race;
  /** @var string type of npc */
  protected $type;
  /** @var string */
  protected $sprite;
  /** @var string */
  protected $portrait;
  /** @var int id of stage */
  protected $stage;
  /** @var int */
  protected $pos_x;
  /** @var int */
  protected $pos_y;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->getName() != "npcs") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>