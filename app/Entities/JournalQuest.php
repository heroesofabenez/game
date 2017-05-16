<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Data structure for quest in journal
 * 
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 */
class JournalQuest {
  use \Nette\SmartObject;
  
  /** @var int Quest's id */
  protected $id;
  /** @var string Quest's name */
  protected $name;
  
  /**
   * @param int $id
   * @param string $name
   */
  function __construct(int $id, string $name) {
    $this->id = $id;
    $this->name = $name;
  }
  
  /**
   * @return int
   */
  function getId(): int {
    return $this->id;
  }
  
  /**
   * @return string
   */
  function getName(): string {
    return $this->name;
  }
}
?>