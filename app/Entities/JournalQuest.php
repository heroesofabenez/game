<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for quest in journal
 * 
 * @author Jakub Konečný
 */
class JournalQuest extends BaseEntity {
  /** @var int Quest's id */
  protected $id;
  /** @var string Quest's name */
  protected $name;
  
  /**
   * @param int $id
   * @param string $name
   */
  function __construct($id, $name) {
    $this->id = (int) $id;
    $this->name = $name;
  }
}
?>