<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Base Skill
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 * @property-read string $description
 * @property-read int $neededClass
 * @property-read int|NULL $neededSpecialization
 * @property-read int $neededLevel
 * @property-read string $target
 * @property-read int $levels
 * @property-read int $cooldown
 */
abstract class BaseSkill {
  use \Nette\SmartObject;
  
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $description;
  /** @var int */
  protected $neededClass;
  /** @var int */
  protected $neededSpecialization;
  /** @var int */
  protected $neededLevel;
  /** @var string */
  protected $target;
  /** @var int */
  protected $levels;
  
  /**
   * @return int
   */
  abstract function getCooldown(): int;
  
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
   * @return int
   */
  function getNeededClass(): int {
    return $this->neededClass;
  }
  
  /**
   * @return int|NULL
   */
  function getNeededSpecialization(): ?int {
    return $this->neededSpecialization;
  }
  
  /**
   * @return int
   */
  function getNeededLevel(): int {
    return $this->neededLevel;
  }
  
  /**
   * @return string
   */
  function getTarget(): string {
    return $this->target;
  }
  
  /**
   * @return int
   */
  function getLevels(): int {
    return $this->levels;
  }
}
?>