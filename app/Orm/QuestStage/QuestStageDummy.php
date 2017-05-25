<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * QuestStageDummy
 *
 * @author Jakub Konečný
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int|NULL $requiredLevel
 * @property int|NULL $requiredRace
 * @property int|NULL $requiredOccupation
 * @property int $area
 * @property int|NULL $posX
 * @property int|NULL $posY
 */
class QuestStageDummy {
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
  /** @var int */
  protected $area;
  /** @var int|NULL */
  protected $posX;
  /** @var int|NULL */
  protected $posY;
  
  function __construct(QuestStage $stage) {
    $this->id = $stage->id;
    $this->name = $stage->name;
    $this->description = $stage->description;
    $this->requiredLevel = $stage->requiredLevel;
    $this->requiredRace = ($stage->requiredRace) ? $stage->requiredRace->id : NULL;
    $this->requiredOccupation = ($stage->requiredOccupation) ? $stage->requiredOccupation->id : NULL;
    $this->area = $stage->area->id;
    $this->posX = $stage->posX;
    $this->posY = $stage->posY;
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
  function getRequiredLevel(): ?int {
    return $this->requiredLevel;
  }
  
  /**
   * @return int|NULL
   */
  function getRequiredRace(): ?int {
    return $this->requiredRace;
  }
  
  /**
   * @return int|NULL
   */
  function getRequiredOccupation(): ?int {
    return $this->requiredOccupation;
  }
  
  /**
   * @return int
   */
  function getArea(): int {
    return $this->area;
  }
  
  /**
   * @return int|NULL
   */
  function getPosX(): ?int {
    return $this->posX;
  }
  
  /**
   * @return int|NULL
   */
  function getPosY(): ?int {
    return $this->posY;
  }
}
?>