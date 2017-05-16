<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Base character skill
 *
 * @author Jakub Konečný
 * @property-read Skill $skill
 * @property int $level
 * @property-read int $cooldown
 * @property-read string $skillType
 */
abstract class CharacterSkill {
  use \Nette\SmartObject;
  
  /** @var Skill */
  protected $skill;
  /** @var int */
  protected $level;
  /** @var int */
  protected $cooldown = 0;
  
  function __construct(Skill $skill, int $level) {
    $this->skill = $skill;
    $this->level = $level;
  }
  
  /**
   * @return int
   */
  function getLevel(): int {
    return $this->level;
  }
  
  /**
   * @return int
   */
  function getCooldown(): int {
    return $this->cooldown;
  }
  
  /**
   * @param int $level
   */
  function setLevel(int $level) {
    $this->level = $level;
  }
  
  /**
   * @return string
   */
  function getSkillType(): string {
    if($this->skill instanceof SkillAttack) {
      return "attack";
    } elseif($this->skill instanceof SkillSpecial) {
      return "special";
    } else {
      return "";
    }
  }
  
  /**
   * @return bool
   */
  function canUse(): bool {
    return ($this->cooldown < 1);
  }
  
  /**
   * @return void
   */
  function resetCooldown(): void {
    $this->cooldown = $this->skill->cooldown;
  }
  
  /**
   * @return void
   */
  function decreaseCooldown(): void {
    if($this->cooldown > 0) {
      $this->cooldown--;
    }
  }
}
?>