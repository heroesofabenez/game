<?php
/**
 * Intro Model
 *
 * @author Jakub Konečný
 */
class Intro {
  /**
   * Get next part of introduction
   * 
   * @param Nette\Database\Context $db Database context
   * @param int $id Character's id
   * @return string Text of current introduction part
   */
  static function getIntroPart(Nette\Database\Context $db, $id) {
    $char = $db->table("characters")->get($id);
    $intros = $db->table("introduction")
      ->where("race", $char->race)
      ->where("class", $char->occupation)
      ->where("part", $this->part);
    if($intros->count("*") == 0) return;
    foreach($intros as $intro) { }
    return $intro->text;
  }
  
  /**
   * Get starting location for the player
   * 
   * @param Nette\Database\Context $db Database context
   * @param Nette\Security\Identity $identity Player's identity
   * @return int id of starting stage
   */
  static function getStartingLocation(Nette\Database\Context $db, Nette\Security\Identity $identity) {
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
