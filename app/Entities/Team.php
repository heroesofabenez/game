<?php
namespace HeroesofAbenez\Entities;

/**
 * Structure for a team in combat
 * 
 * @author Jakub Konečný
 * @property Character[] $items Characters in the team
 * @property-read Character[] $activeMembers
 * @property-read Character[] $aliveMembers
 * @property-read Character[] $usableMembers
 * @property-read int[] $used
 */
class Team extends BaseEntity implements \ArrayAccess, \Countable, \IteratorAggregate {
  /** @var string Name of the team */
  protected $name;
  /** @var int[] Characters used in the current round */
  protected $used = [];
  
  use \HeroesofAbenez\Utils\TCollection;
  
  /**
   * @param string $name Name of the team
   */
  function __construct($name) {
    $this->name = (string) $name;
    $this->class = \HeroesofAbenez\Entities\Character::class;
  }
  
  /**
   * @return int[]
   */
  function getUsed() {
    return $this->used;
  }
  
  /**
   * Get member's index
   * 
   * @param int $id Character's id
   * @return int Index|-1 if the character is not in the team
   */
  function getIndex($id) {
    foreach($this->items as $index => $member) {
      if($member->id === $id) return $index;
    }
    return -1;
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
    $return = [];
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
    $return = [];
    foreach($this->items as $member) {
      if($member->hitpoints > 0) $return[] = $member;
    }
    return $return;
  }
  
  /**
   * Get members which can perform an action
   * 
   * @return Character[]
   */
  function getUsableMembers() {
    $return = [];
    foreach($this->items as $index => $member) {
      if(!$member->stunned AND $member->hitpoints > 0 AND !in_array($index, $this->used)) $return[] = $member;
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
   * Mark member as used in this round
   * 
   * @param int $index
   * @return void
   */
  function useMember($index) {
    if(!is_int($index) OR !$this->offsetExists($index)) return;
    elseif(in_array($index, $this->used)) return;
    $this->used[] = $index;
  }
  
  /**
   * Clear list of used members
   * 
   * @return void
   */
  function clearUsed() {
    $this->used = [];
  }
}
?>