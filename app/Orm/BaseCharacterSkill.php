<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Base character skill
 *
 * @author Jakub Konečný
 * @property-read BaseSkill $skill
 * @property int $level
 * @property-read int $cooldown
 * @property-read string $skillType
 */
abstract class BaseCharacterSkill {
  use \Nette\SmartObject;
  
  /** @var BaseSkill */
  protected $skill;
  /** @var int */
  protected $level;
  /** @var int */
  protected $cooldown = 0;
  
  function __construct(BaseSkill $skill, int $level) {
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
    if($this->skill instanceof SkillAttackDummy) {
      return "attack";
    } elseif($this->skill instanceof SkillSpecialDummy) {
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