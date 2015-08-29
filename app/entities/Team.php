<?php
namespace HeroesofAbenez\Entities;

/**
 * Structure for a team in combat
 * 
 * @author Jakub Konečný
 * @property-read Character[] $activeMembers
 * @property-read Character[] $aliveMembers
 */
class Team extends BaseEntity implements \Countable, \IteratorAggregate {
  /** @var string Name of the team */
  protected $name;
  /** @var Character[] Characters in the team */
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
   * Check if the team has a character
   * 
   * @param int $id Character's id
   * @return boolean
   */
  function hasMember($id) {
    foreach($this->members as $member) {
      if($member->id === $id) return true;
    }
    return false;
  }
  
  /**
   * Get active members (alive and not stunned) from the team
   * 
   * @return Character[]
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
   * @return Character[]
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
  
  /**
   * @return int
   */
  function count() {
    return count($this->members);
  }
  
  /**
   * @return \ArrayIterator
   */
  function getIterator() {
    return new \ArrayIterator($this->members);
  }
}
?>