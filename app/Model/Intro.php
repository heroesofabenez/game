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
  
  public function __construct(\Nette\Security\User $user, ORM $orm) {
    $this->user = $user;
    $this->orm = $orm;
  }
  
  /**
   * Check in which part of intro the player is
   */
  public function getIntroPosition(): ?int {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    return $character->intro;
  }
  
  /**
   * Get a part of introduction
   */
  public function getIntroPart(int $part): string {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $intro = $this->orm->introduction->getBy([
      "race" => $character->race->id,
      "class" => $character->occupation->id,
      "part" => $part
    ]);
    if(is_null($intro)) {
      return "";
    }
    return $intro->text;
  }
  
  /**
   * Move onto next part of introduction
   */
  public function moveToNextPart(int $part): void {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $character->intro = $part;
    $this->orm->characters->persistAndFlush($character);
  }
  
  /**
   * Get starting location for the player
   */
  public function getStartingLocation(): int {
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
   */
  public function endIntro(): void {
    $startingLocation = $this->getStartingLocation();
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $character->currentStage = $startingLocation;
    $this->orm->characters->persistAndFlush($character);
  }
}
?>