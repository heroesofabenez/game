<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Data structure for character class
 *
 * @author Jakub Konečný
 */
class CharacterClass extends BaseEntity {
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $description;
  /** @var int */
  protected $strength;
  /** @var int */
  protected $strength_grow;
  /** @var int */
  protected $dexterity;
  /** @var int */
  protected $dexterity_grow;
  /** @var int */
  protected $constitution;
  /** @var int */
  protected $constitution_grow;
  /** @var int */
  protected $intelligence;
  /** @var int */
  protected $intelligence_grow;
  /** @var int */
  protected $charisma;
  /** @var int */
  protected $charisma_grow;
  /** @var int */
  protected $stat_points_level;
  /** @var int */
  protected $initiative;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->getName() != "character_classess") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>