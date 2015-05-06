<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;

class Team extends Nette\Object {
  private $name;
  private $members = array();
  function __construct($name) {
    if(!is_string($name)) { exit("Invalid value for parameter name passed to method Team::__construct. Expected string."); }
    else { $this->$name = $name; }
  }
  
  function addMember($member) {
    if(!is_a($member, "Character")) { exit("Invalid value for parameter member passed to method Team:addMember. Expected Character."); }
    else { $this->members[] = $member; }
  }
}

class CombatBase extends Nette\Object {
  private $team1 = array();
  private $team2 = array();
  private $round;
  function __construct($team1, $team2) {
    if(!is_a($team1, "Team")) { exit("Invalid value for parameter team1 passed to method CombatBase:__construct. Expected Team."); }
    if(!is_a($team2, "Team")) { exit("Invalid value for parameter team2 passed to method CombatBase:__construct. Expected Team."); }
    $this->round = 0;
    $this->team1 = $team1;
    $this->team2 = $team2;
  }
  
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