<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;

class Team extends Object {
  private $name;
  private $members = array();
  function __construct($name) {
    if(!is_string($name)) { exit("Invalid value for parameter name passed to method Team::__construct. Expected string."); }
    else ( $this->$name = $name; )
  }
  
  function addMember($member) {
    if(!is_a($member, "Character")) { exit("Invalid value for parameter member passed to method Team:addMember. Expected Character."); }
    else { $this->members[] = $member; }
  }
}

class CombatBase extends Object {
  private $team1 = array();
  private $team2 = array();
  private $round;
  function __construct($team1, $team2) { }
  function execute() { }
}
?>