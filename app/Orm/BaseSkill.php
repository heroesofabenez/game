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
  /** @var int|NULL */
  protected $neededSpecialization;
  /** @var int */
  protected $neededLevel;
  /** @var string */
  protected $target;
  /** @var int */
  protected $levels;
  
  abstract public function getCooldown(): int;
  
  public function getId(): int {
    return $this->id;
  }
  
  public function getName(): string {
    return $this->name;
  }
  
  public function getDescription(): string {
    return $this->description;
  }
  
  public function getNeededClass(): int {
    return $this->neededClass;
  }
  
  public function getNeededSpecialization(): ?int {
    return $this->neededSpecialization;
  }
  
  public function getNeededLevel(): int {
    return $this->neededLevel;
  }
  
  public function getTarget(): string {
    return $this->target;
  }
  
  public function getLevels(): int {
    return $this->levels;
  }
}
?>