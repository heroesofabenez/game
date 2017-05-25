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
  
  function __construct(\Nette\Security\User $user, ORM $orm) {
    $this->user = $user;
    $this->orm = $orm;
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
    $intro = $this->orm->introduction->getBy([
      "race" => $char->race->id,
      "class" => $char->occupation->id,
      "part" => $part
    ]);
    if(is_null($intro)) {
      return "";
    } else {
      return $intro->text;
    }
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
    $classSL = $this->orm->stages->getClassStartingLocation($this->user->identity->occupation);
    if(!is_null($classSL)) {
      return $classSL->id;
    }
    $raceSL = $this->orm->stages->getRaceStartingLocation($this->user->identity->occupation);
    if(!is_null($raceSL)) {
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
    $character = $this->orm->characters->getById($this->user->id);
    $character->currentStage = $startingLocation;
    $this->orm->characters->persistAndFlush($character);
  }
}
?>