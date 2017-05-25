<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * PetTypeDummy
 *
 * @author Jakub Konečný
 * @property int $id
 * @property string $name
 * @property string $bonusStat
 * @property int $bonusValue
 * @property string $image
 * @property int $requiredLevel
 * @property int|NULL $requiredClass
 * @property int|NULL $requiredRace
 * @property int $cost
 */
class PetTypeDummy {
  use \Nette\SmartObject;
  
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $bonusStat;
  /** @var int */
  protected $bonusValue;
  /** @var string */
  protected $image;
  /** @var int */
  protected $requiredLevel;
  /** @var int|NULL */
  protected $requiredClass;
  /** @var int|NULL */
  protected $requiredRace;
  /** @var int */
  protected $cost;
  
  function __construct(PetType $type) {
    $this->id = $type->id;
    $this->name = $type->name;
    $this->bonusStat = $type->bonusStat;
    $this->bonusValue = $type->bonusValue;
    $this->image = $type->image;
    $this->requiredLevel = $type->requiredLevel;
    $this->requiredClass = ($type->requiredClass) ? $type->requiredClass->id : NULL;
    $this->requiredRace = ($type->requiredRace) ? $type->requiredRace->id : NULL;
    $this->cost = $type->cost;
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
  function getBonusStat(): string {
    return $this->bonusStat;
  }
  
  /**
   * @return int
   */
  function getBonusValue(): int {
    return $this->bonusValue;
  }
  
  /**
   * @return string
   */
  function getImage(): string {
    return $this->image;
  }
  
  /**
   * @return int
   */
  function getRequiredLevel(): int {
    return $this->requiredLevel;
  }
  
  /**
   * @return int|NULL
   */
  function getRequiredClass() {
    return $this->requiredClass;
  }
  
  /**
   * @return int|NULL
   */
  function getRequiredRace() {
    return $this->requiredRace;
  }
  
  /**
   * @return int
   */
  function getCost(): int {
    return $this->cost;
  }
  
  /**
   * @return string
   */
  function __toString(): string {
    return $this->name;
  }
}
?>