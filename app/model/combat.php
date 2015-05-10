<?php
/**
 * Structure for a team in combat
 * 
 * @author Jakub Konečný
 */
class Team extends Nette\Object {
  /** @var string Name of the team */
  protected $name;
  /** @var array Characters in the team */
  protected $members = array();
  
  /**
   * @param string $name Name of the team
   */
  function __construct($name) {
    if(!is_string($name)) { exit("Invalid value for parameter name passed to method Team::__construct. Expected string."); }
    else { $this->$name = $name; }
  }
  
  /**
   * Adds a member to the team
   * 
   * @param Character $member Member to be added to the team
   * 
   * @return void
   */
  function addMember($member) {
    if(!is_a($member, "Character")) { exit("Invalid value for parameter member passed to method Team:addMember. Expected Character."); }
    else { $this->members[] = $member; }
  }
}

/**
 * Handles combat
 * 
 * @author Jakub Konečný
 */
class CombatBase extends Nette\Object {
  /** @var array First team */
  protected $team1 = array();
  /** @var array Second team */
  protected $team2 = array();
  /** @var int number of current round */
  protected $round;
  
  /**
   * @param Team $team1 First team
   * @param Team $team2 Second team
   */
  function __construct($team1, $team2) {
    if(!is_a($team1, "Team")) { exit("Invalid value for parameter team1 passed to method CombatBase:__construct. Expected Team."); }
    if(!is_a($team2, "Team")) { exit("Invalid value for parameter team2 passed to method CombatBase:__construct. Expected Team."); }
    $this->round = 0;
    $this->team1 = $team1;
    $this->team2 = $team2;
  }
  
  /**
   * Starts next round
   * 
   * @return void 
   */
  function start_round() {
    $this->round++;
    foreach($this->team1 as &$character) {
    	foreach($character->effects as &$effect) {
     	  if(is_int($effect->duration)) { $effect->duration--; }
     }
     $character->recalculateStats();
    }
    foreach($this->team2 as &$character) {
    	foreach($character->effects as &$effect) {
     	  if(is_int($effect->duration)) { $effect->duration--; }
     }
     $character->recalculateStats();
    }
  }
  
  function execute() { }
}
?>