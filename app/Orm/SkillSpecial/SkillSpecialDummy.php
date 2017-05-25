<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * SkillSpecialDummy
 *
 * @author Jakub Konečný
 * @property-read string $type
 * @property-read string|NULL $stat
 * @property-read int $value
 * @property-read int $valueGrowth
 * @property-read int $duration
 */
class SkillSpecialDummy extends BaseSkill {
  /** @var string */
  protected $type;
  /** @var string|NULL */
  protected $stat;
  /** @var int */
  protected $value;
  /** @var int */
  protected $valueGrowth;
  /** @var int */
  protected $duration;
  
  function __construct(SkillSpecial $skill) {
    $this->id = $skill->id;
    $this->name = $skill->name;
    $this->description = $skill->description;
    $this->neededClass = $skill->neededClass->id;
    $this->neededSpecialization = ($skill->neededSpecialization) ? $skill->neededSpecialization->id : NULL;
    $this->neededLevel = $skill->neededLevel;
    $this->type = $skill->type;
    $this->target = $skill->target;
    $this->stat = $skill->stat;
    $this->value = $skill->value;
    $this->valueGrowth = $skill->valueGrowth;
    $this->levels = $skill->levels;
    $this->duration = $skill->duration;
  }
  
  /**
   * @return int
   */
  function getCooldown(): int {
    return 5;
  }
  
  /**
   * @return string
   */
  function getType(): string {
    return $this->type;
  }
  
  /**
   * @return string|NULL
   */
  function getStat(): ?string {
    return $this->stat;
  }
  
  /**
   * @return int
   */
  function getValue(): int {
    return $this->value;
  }
  
  /**
   * @return int
   */
  function getValueGrowth(): int {
    return $this->valueGrowth;
  }
  
  /**
   * @return int
   */
  function getDuration(): int {
    return $this->duration;
  }
}
?>