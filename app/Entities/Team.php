<?php
declare(strict_types=1);

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
  function __construct(string $name) {
    $this->name = $name;
    $this->class = Character::class;
  }
  
  /**
   * @return int[]
   */
  function getUsed(): array {
    return $this->used;
  }
  
  /**
   * Get member's index
   * 
   * @param int|string $id Character's id
   * @return int|NULL Index|NULL if the character is not in the team
   */
  function getIndex($id): ?int {
    foreach($this->items as $index => $member) {
      if($member->id === $id) {
        return $index;
      }
    }
    return NULL;
  }
  
  /**
   * Check if the team has a character
   * 
   * @param string $id Character's id
   * @return boolean
   */
  function hasMember($id): bool {
    foreach($this->items as $member) {
      if($member->id === $id) {
        return true;
      }
    }
    return false;
  }
  
  /**
   * Get active members (alive and not stunned) from the team
   * 
   * @return Character[]
   */
  function getActiveMembers(): array {
    $return = [];
    foreach($this->items as $member) {
      if(!$member->stunned AND $member->hitpoints > 0) {
        $return[] = $member;
      }
    }
    return $return;
  }
  
  /**
   * Get alive members from the team
   * 
   * @return Character[]
   */
  function getAliveMembers(): array {
    $return = [];
    foreach($this->items as $member) {
      if($member->hitpoints > 0) {
        $return[] = $member;
      }
    }
    return $return;
  }
  
  /**
   * Get members which can perform an action
   * 
   * @return Character[]
   */
  function getUsableMembers(): array {
    $return = [];
    foreach($this->items as $index => $member) {
      if(!$member->stunned AND $member->hitpoints > 0 AND !in_array($index, $this->used)) {
        $return[] = $member;
      }
    }
    return $return;
  }
  
  /**
   * Check whetever the team has at least 1 active member
   * 
   * @return bool
   */
  function hasActiveMembers(): bool {
    return count($this->getActiveMembers()) > 0;
  }
  
  /**
   * Check whetever the team has at least 1 alive member
   * 
   * @return bool
   */
  function hasAliveMembers(): bool {
    return count($this->getAliveMembers()) > 0;
  }
}
?>