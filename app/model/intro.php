<?php
/**
 * Intro Model
 *
 * @author Jakub Konečný
 */
class Intro {
  /**
   * Check in which part of intro the player is
   * 
   * @param Nette\Database\Context $db Database context
   * @param type $uid User's id
   * @return type
   */
  static function getIntroPosition(Nette\Database\Context $db, $uid) {
    $char = $db->table("characters")->get($uid);
    return $char->intro;
  }
  /**
   * Get a part of introduction
   * 
   * @param Nette\Database\Context $db Database context
   * @param int $id Character's id
   * @param int $part Part's id
   * @return string Text of current introduction part
   */
  static function getIntroPart(Nette\Database\Context $db, $id, $part) {
    $char = $db->table("characters")->get($id);
    $intros = $db->table("introduction")
      ->where("race", $char->race)
      ->where("class", $char->occupation)
      ->where("part", $part);
    if($intros->count("*") == 0) return;
    foreach($intros as $intro) { }
    return $intro->text;
  }
  
  /**
   * Move onto next part of introduction
   * 
   * @param int $part
   * @param int $id Player's id
   * @param Nette\Database\Context $db Database context
   */
  static function moveToNextPart($part, $id, Nette\Database\Context $db) {
    $data = array("intro" => $part);
    $db->query("UPDATE characters SET ? WHERE id=?", $data, $id);
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
  
  /**
   * Ends introuction and sends player to his starting location
   * 
   * @param Nette\Database\Context $db Database context
   * @param Nette\Security\Identity $identity Player's identity
   * @return void
   */
  static function endIntro(Nette\Database\Context $db, Nette\Security\Identity $identity) {
    $startingLocation = Intro::getStartingLocation($db, $identity);
    $data = array("current_stage" => $startingLocation);
    $db->query("UPDATE characters SET ? WHERE id=?", $data, $identity->id);
  }
}
