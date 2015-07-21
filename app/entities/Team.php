<?php
namespace HeroesofAbenez\Entities;

/**
 * Structure for a team in combat
 * 
 * @author Jakub Konečný
 * @property-read array $activeMembers
 * @property-read array $aliveMembers
 */
class Team extends BaseEntity implements \Iterator {
  /** @var string Name of the team */
  protected $name;
  /** @var array Characters in the team */
  protected $members = array();
  /** @var int */
  protected $pos;
  
  /**
   * @param string $name Name of the team
   */
  function __construct($name) {
    if(!is_string($name)) exit("Invalid value for parameter name passed to method Team::__construct. Expected string.");
    else $this->name = $name;
    $this->pos = 0;
  }
  
  /**
   * Adds a member to the team
   * 
   * @param \HeroesofAbenez\Entities\Character $member Member to be added to the team
   * 
   * @return void
   */
  function addMember(Character $member) {
    $this->members[] = $member;
  }
  
  /**
   * Get active members (alive and not stunned) from the team
   * 
   * @return array
   */
  function getActiveMembers() {
    $return = array();
    foreach($this->members as $member) {
      if(!$member->stunned AND $member->hitpoints > 0) $return[] = $member;
    }
    return $return;
  }
  
  /**
   * Get alive members from the team
   * 
   * @return array
   */
  function getAliveMembers() {
    $return = array();
    foreach($this->members as $member) {
      if($member->hitpoints > 0) $return[] = $member;
    }
    return $return;
  }
  
  /**
   * Check whetever the team has at least 1 active member
   * 
   * @return bool
   */
  function hasActiveMembers() {
    return count($this->getActiveMembers()) > 0;
  }
  
  /**
   * Check whetever the team has at least 1 alive member
   * 
   * @return bool
   */
  function hasAliveMembers() {
    return count($this->getAliveMembers()) > 0;
  }
  
  function rewind() {
    $this->pos = 0;
  }
  
  function current() {
    return $this->members[$this->pos];
  }
  
  function key() {
    return $this->pos;
  }
  
  function next() {
    ++$this->pos;
  }
  
  function valid() {
    return isset($this->members[$this->pos]);
  }
}
?>