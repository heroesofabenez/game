<?php
namespace HeroesofAbenez\Entities;

/**
 * Structure for a team in combat
 * 
 * @author Jakub Konečný
 */
class Team extends \Nette\Object {
  /** @var string Name of the team */
  protected $name;
  /** @var array Characters in the team */
  protected $members = array();
  
  /**
   * @param string $name Name of the team
   */
  function __construct($name) {
    if(!is_string($name)) exit("Invalid value for parameter name passed to method Team::__construct. Expected string.");
    else $this->$name = $name;
  }
  
  /**
   * Adds a member to the team
   * 
   * @param Character $member Member to be added to the team
   * 
   * @return void
   */
  function addMember($member) {
    if(!is_a($member, "Character")) exit("Invalid value for parameter member passed to method Team:addMember. Expected Character.");
    else $this->members[] = $member;
  }
}
?>