<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM;

/**
 * Intro Model
 *
 * @author Jakub Konečný
 */
class Intro {
  use \Nette\SmartObject;
  
  /** @var \Nette\Security\User */
  protected $user;
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Database\Context */
  protected $db;
  
  function __construct(\Nette\Security\User $user, ORM $orm, \Nette\Database\Context $db) {
    $this->user = $user;
    $this->orm = $orm;
    $this->db = $db;
  }
  
  /**
   * Check in which part of intro the player is
   * 
   * @return int
   */
  function getIntroPosition(): int {
    return $this->orm->characters->getById($this->user->id)->intro;
  }
  /**
   * Get a part of introduction
   * 
   * @param int $part Part's id
   * @return string Text of current introduction part
   */
  function getIntroPart(int $part): string {
    $char = $this->orm->characters->getById($this->user->id);
    $intros = $this->db->table("introduction")
      ->where("race", $char->race->id)
      ->where("class", $char->occupation->id)
      ->where("part", $part);
    if($intros->count() == 0) {
      return "";
    }
    $intro = $intros->fetch();
    return $intro->text;
  }
  
  /**
   * Move onto next part of introduction
   *
   * @param int $part
   * @return void
   */
  function moveToNextPart(int $part): void {
    $character = $this->orm->characters->getById($this->user->id);
    $character->intro = $part;
    $this->orm->characters->persistAndFlush($character);
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
  function endIntro(): void {
    $startingLocation = $this->getStartingLocation();
    $data = ["current_stage" => $startingLocation];
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $this->user->id);
  }
}
?>