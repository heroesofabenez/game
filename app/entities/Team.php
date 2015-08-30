<?php
namespace HeroesofAbenez\Entities;

/**
 * Structure for a team in combat
 * 
 * @author Jakub Konečný
 * @property Character[] $items
 * @property-read Character[] $activeMembers
 * @property-read Character[] $aliveMembers
 */
class Team extends BaseEntity implements \ArrayAccess, \Countable, \IteratorAggregate {
  /** @var string Name of the team */
  protected $name;
  /** @var Character[] Characters in the team */
  protected $members = array();
  
   use \HeroesofAbenez\Utils\TCollection;
  
  /**
   * @param string $name Name of the team
   */
  function __construct($name) {
    $this->name = (string) $name;
    $this->class = '\HeroesofAbenez\Entities\Character';
  }
  
  /**
   * Check if the team has a character
   * 
   * @param int $id Character's id
   * @return boolean
   */
  function hasMember($id) {
    foreach($this->items as $member) {
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
    foreach($this->items as $member) {
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
    foreach($this->items as $member) {
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
}
?>