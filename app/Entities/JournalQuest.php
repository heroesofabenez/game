<?php
declare(strict_types=1);

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
  function __construct(int $id, string $name) {
    $this->id = (int) $id;
    $this->name = $name;
  }
}
?>