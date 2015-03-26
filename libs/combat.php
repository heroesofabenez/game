<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
class CombatBase extends Object {
  private $team1 = array();
  private $team2 = array();
  private $round;
  function __construct($team1, $team2) { }
  function execute() { }
}
?>