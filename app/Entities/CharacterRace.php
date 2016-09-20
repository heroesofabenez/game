<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Data structure for race
 *
 * @author Jakub Konečný
 */
class CharacterRace extends BaseEntity {
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $description;
  /** @var int */
  protected $strength;
  /** @var int */
  protected $dexterity;
  /** @var int */
  protected $constitution;
  /** @var int */
  protected $intelligence;
  /** @var int */
  protected $charisma;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->getName() != "character_races") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>