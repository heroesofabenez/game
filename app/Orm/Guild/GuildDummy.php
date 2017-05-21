<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Data structure for guild
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 * @property-read string $description
 * @property-read int $members
 * @property-read string $leader
 */
class GuildDummy {
  use \Nette\SmartObject;
  
  /** @var int id */
  protected $id;
  /** @var string name */
  protected $name;
  /** @var string description */
  protected $description;
  /** @var int number of members */
  protected $members;
  /** @var string name of leader */
  protected $leader;
  
  /**
   * @param int $id id
   * @param string $name name
   * @param string $description description
   * @param int $members number of members
   * @param string $leader name of leader
   */
  function __construct(int $id, string $name, string $description, int $members = 0, $leader = "") {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->members = $members;
    $this->leader = $leader;
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
  
  /**
   * @return string
   */
  function getDescription(): string {
    return $this->description;
  }
  
  /**
   * @return int
   */
  function getMembers(): int {
    return $this->members;
  }
  
  /**
   * @return string
   */
  function getLeader(): string {
    return $this->leader;
  }
  
  /**
   * Converts entity into array
   *
   * @return array
   */
  function toArray(): array {
    $return = [];
    foreach($this as $key => $value) {
      $return[$key] = $value;
    }
    return $return;
  }
}
?>