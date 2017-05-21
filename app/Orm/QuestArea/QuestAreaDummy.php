<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * QuestAreaDummy
 *
 * @author Jakub Konečný
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int|NULL $requiredLevel
 * @property int|NULL $requiredRace
 * @property int|NULL $requiredOccupation
 * @property int|NULL $posX
 * @property int|NULL $posY
 */
class QuestAreaDummy {
  use \Nette\SmartObject;
  
  /** @var int id */
  protected $id;
  /** @var string name */
  protected $name;
  /** @var string description */
  protected $description;
  /** @var int|NULL minimum level to enter stage */
  protected $requiredLevel;
  /** @var int|NULL id of race needed to enter stage */
  protected $requiredRace;
  /** @var int|NULL id of class needed to enter stage */
  protected $requiredOccupation;
  /** @var int|NULL */
  protected $posX;
  /** @var int|NULL */
  protected $posY;
  
  function __construct(QuestArea $area) {
    $this->id = $area->id;
    $this->name = $area->name;
    $this->description = $area->description;
    $this->requiredLevel = $area->requiredLevel;
    $this->requiredRace = ($area->requiredRace) ? $area->requiredRace->id : NULL;
    $this->requiredOccupation = ($area->requiredOccupation) ? $area->requiredOccupation->id : NULL;
    $this->posX = $area->posX;
    $this->posY = $area->posY;
  }
  
  /**
   * @return int
   */
  function getId(): int {
    return $this->id;
  }
  
  /**
   * @return string
   */
  function getName(): string {
    return $this->name;
  }
  
  /**
   * @return string
   */
  function getDescription(): string {
    return $this->description;
  }
  
  /**
   * @return int|NULL
   */
  function getRequiredLevel() {
    return $this->requiredLevel;
  }
  
  /**
   * @return int|NULL
   */
  function getRequiredRace() {
    return $this->requiredRace;
  }
  
  /**
   * @return int|NULL
   */
  function getRequiredOccupation() {
    return $this->requiredOccupation;
  }
  
  /**
   * @return int|NULL
   */
  function getPosX() {
    return $this->posX;
  }
  
  /**
   * @return int|NULL
   */
  function getPosY() {
    return $this->posY;
  }
}
?>