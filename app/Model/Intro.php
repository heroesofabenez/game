<?php
namespace HeroesofAbenez\Model;

/**
 * Intro Model
 *
 * @author Jakub Konečný
 */
class Intro {
  use \Nette\SmartObject;
  
  /** @var \Nette\Security\User */
  protected $user;
  /** @var \Nette\Database\Context */
  protected $db;
  
  /**
   * @param \Nette\Security\User $user
   * @param \Nette\Database\Context $db
   */
  function __construct(\Nette\Security\User $user, \Nette\Database\Context $db) {
    $this->user = $user;
    $this->db = $db;
  }
  
  /**
   * Check in which part of intro the player is
   * 
   * @return int
   */
  function getIntroPosition(): int {
    $char = $this->db->table("characters")->get($this->user->id);
    return $char->intro;
  }
  /**
   * Get a part of introduction
   * 
   * @param int $part Part's id
   * @return string Text of current introduction part
   */
  function getIntroPart(int $part): string {
    $char = $this->db->table("characters")->get($this->user->id);
    $intros = $this->db->table("introduction")
      ->where("race", $char->race)
      ->where("class", $char->occupation)
      ->where("part", $part);
    if($intros->count() == 0) return "";
    $intro = $intros->fetch();
    return $intro->text;
  }
  
  /**
   * Move onto next part of introduction
   * 
   * @return void
   */
  function moveToNextPart(int $part) {
    $data = ["intro" => $part];
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $this->user->id);
  }
  
  /**
   * Get starting location for the player
   * 
   * @return int id of starting stage
   */
  function getStartingLocation(): int {
    $classSLs = $this->db->table("quest_stages")
      ->where("required_level", 0)
      ->where("required_occupation", $this->user->identity->occupation);
    if($classSLs->count() > 0) {
      $classSL = $classSLs->fetch();
      return $classSL->id;
    }
    $raceSLs = $this->db->table("quest_stages")
      ->where("required_level", 0)
      ->where("required_race", $this->user->identity->race);
    if($raceSLs->count() > 0) {
      $raceSL = $raceSLs->fetch();
      return $raceSL->id;
    }
  }
  
  /**
   * Ends introduction and sends player to his starting location
   * 
   * @return void
   */
  function endIntro() {
    $startingLocation = Intro::getStartingLocation();
    $data = ["current_stage" => $startingLocation];
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $this->user->id);
  }
}
?>