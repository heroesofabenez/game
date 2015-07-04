<?php
namespace HeroesofAbenez;

/**
 * Data structure for quest in journal
 * 
 * @author Jakub Konečný
 */
class JournalQuest extends \Nette\Object {
  /** @var int Quest's id */
  public $id;
  /** @var string Quest's name */
  public $name;
  
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