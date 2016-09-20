<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for guild
 * 
 * @author Jakub Konečný
 */
class Guild extends BaseEntity {
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
}
?>