<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for character class
 *
 * @author Jakub Konečný
 */
class CharacterSpecialization extends BaseEntity {
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var int */
  protected $class;
  /** @var float */
  protected $strength_grow;
  /** @var float */
  protected $dexterity_grow;
  /** @var float */
  protected $constitution_grow;
  /** @var float */
  protected $intelligence_grow;
  /** @var float */
  protected $charisma_grow;
  /** @var float */
  protected $stat_points_level;
  
  function __construct(\Nette\Database\Table\ActiveRow $row) {
    if($row->getTable()->getName() != "character_specializations") exit;
    foreach($row as $key => $value) {
      $this->$key = $value;
    }
  }
}
?>