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
  
  abstract function getCooldown(): int;
  
  function getId(): int {
    return $this->id;
  }
  
  function getName(): string {
    return $this->name;
  }
  
  function getDescription(): string {
    return $this->description;
  }
  
  function getNeededClass(): int {
    return $this->neededClass;
  }
  
  function getNeededSpecialization(): ?int {
    return $this->neededSpecialization;
  }
  
  function getNeededLevel(): int {
    return $this->neededLevel;
  }
  
  function getTarget(): string {
    return $this->target;
  }
  
  function getLevels(): int {
    return $this->levels;
  }
}
?>