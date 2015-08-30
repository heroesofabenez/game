<?php
namespace HeroesofAbenez\Entities;

/**
 * Structure for a team in combat
 * 
 * @author Jakub Konečný
 * @property-read Character[] $activeMembers
 * @property-read Character[] $aliveMembers
 */
class Team extends BaseEntity implements \ArrayAccess, \Countable, \IteratorAggregate {
  /** @var string Name of the team */
  protected $name;
  /** @var Character[] Characters in the team */
  protected $members = array();
  
  /**
   * @param string $name Name of the team
   */
  function __construct($name) {
    $this->name = (string) $name;
  }
  
  /**
   * Adds a member to the team
   * 
   * @deprecated
   * @param \HeroesofAbenez\Entities\Character $member Member to be added to the team
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
  
  /**
   * @param int $index
   * @return bool
   */
  function offsetExists($index) {
    return $index >= 0 AND $index < count($this->members);
  }
  
  /**
   * @param int $index
   * @return Character
   * @throws \Nette\OutOfRangeException
   */
  function offsetGet($index) {
    if($index < 0 OR $index >= count($this->members)) {
      throw new \Nette\OutOfRangeException("Offset invalid or out of range.");
    }
    return $this->members[(int) $index];
  }
  
  /**
   * @param int $index
   * @param \HeroesofAbenez\Entities\Character $member
   * @return void
   * @throws \Nette\OutOfRangeException
   */
  function offsetSet($index, $member) {
    if(!$member instanceof Character) throw new Nette\InvalidArgumentException("Argument must be of Character type.");
    if($index === NULL) {
      $this->members[] = $member;
    } elseif($index < 0 OR $index >= count($this->members)) {
      throw new \Nette\OutOfRangeException("Offset invalid or out of range.");
    } else {
      $this->members[(int) $index] = $member;
    }
  }
  
  /**
   * @param int $index
   * @return void
   */
  function offsetUnset($index) {
    if($index < 0 OR $index >= count($this->members)) {
      throw new \Nette\OutOfRangeException("Offset invalid or out of range.");
    }
    array_splice($this->members, (int) $index, 1);
  }
}
?>