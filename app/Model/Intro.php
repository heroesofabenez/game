<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\Model as ORM;

/**
 * Intro Model
 *
 * @author Jakub Konečný
 */
final class Intro {
  use \Nette\SmartObject;
  
  public function __construct(private readonly \Nette\Security\User $user, private readonly ORM $orm) {
  }
  
  /**
   * Check in which part of intro the player is
   */
  // @phpstan-ignore return.unusedType
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
      "class" => $character->class->id,
      "part" => $part
    ]);
    if($intro === null) {
      return "";
    }
    return $intro->text;
  }
  
  /**
   * Move onto next part of introduction
   */
  public function moveToNextPart(): void {
    /** @var \HeroesofAbenez\Orm\Character $character */
    $character = $this->orm->characters->getById($this->user->id);
    $character->intro++;
    $this->orm->characters->persistAndFlush($character);
  }
  
  /**
   * Get starting location for the player
   */
  public function getStartingLocation(): int {
    $classSL = $this->orm->stages->getClassStartingLocation($this->user->identity->class);
    if($classSL !== null) {
      return $classSL->id;
    }
    $raceSL = $this->orm->stages->getRaceStartingLocation($this->user->identity->race);
    if($raceSL !== null) {
      return $raceSL->id;
    }
    return 0;
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