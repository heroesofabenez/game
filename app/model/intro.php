<?php
/**
 * Intro Model
 *
 * @author Jakub Konečný
 */
class Intro {
  /**
   * Get starting location for the player
   * 
   * @param Nette\Database\Context $db Database context
   * @param Nette\Security\Identity $identity Player's identity
   * @return int id of starting stage
   */
  static function getStartingLocation($db, $identity) {
    $classRow = $db->table("character_classess")
      ->where("name", $identity->occupation);
    foreach($classRow as $classR) {  }
    $classSLs = $db->table("quest_stages")
      ->where("required_level", 0)
      ->where("required_occupation", $classR->id);
    if($classSLs->count("id") > 0) {
      foreach($classSLs as $classSL) { }
      return $classSL->id;
    }
    $raceRow = $db->table("character_races")
      ->where("name", $identity->race);
    foreach($raceRow as $raceR) {  }
    $raceSLs = $db->table("quest_stages")
      ->where("required_level", 0)
      ->where("required_race", $raceR->id);
    if($raceSLs->count("id") > 0) {
      foreach($raceSLs as $raceSL) { }
      return $raceSL->id;
    }
  }
}
