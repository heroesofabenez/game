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
  
  function getLevel(): int {
    return $this->level;
  }
  
  function getCooldown(): int {
    return $this->cooldown;
  }
  
  function setLevel(int $level) {
    $this->level = $level;
  }
  
  function getSkillType(): string {
    if($this->skill instanceof SkillAttackDummy) {
      return "attack";
    } elseif($this->skill instanceof SkillSpecialDummy) {
      return "special";
    } else {
      return "";
    }
  }
  
  function canUse(): bool {
    return ($this->cooldown < 1);
  }
  
  function resetCooldown(): void {
    $this->cooldown = $this->skill->cooldown;
  }
  
  function decreaseCooldown(): void {
    if($this->cooldown > 0) {
      $this->cooldown--;
    }
  }
}
?>