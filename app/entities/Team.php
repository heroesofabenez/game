<?php
namespace HeroesofAbenez\Entities;

/**
 * Structure for a team in combat
 * 
 * @author Jakub Konečný
 * @property-read array $activeMembers
 */
class Team extends BaseEntity {
  /** @var string Name of the team */
  protected $name;
  /** @var array Characters in the team */
  protected $members = array();
  
  /**
   * @param string $name Name of the team
   */
  function __construct($name) {
    if(!is_string($name)) exit("Invalid value for parameter name passed to method Team::__construct. Expected string.");
    else $this->name = $name;
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
}
?>