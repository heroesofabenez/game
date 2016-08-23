<?php
namespace HeroesofAbenez\Entities;

/**
 * Base character skill
 *
 * @author Jakub Konečný
 * @property-read string $skillType
 */
abstract class CharacterSkill extends BaseEntity {
  /** @var SkillAttack */
  protected $skill;
  /** @var int */
  protected $level;
  /** @var int */
  protected $cooldown = 0;
  
  function __construct(Skill $skill, $level) {
    $this->skill = $skill;
    $this->level = $level;
  }
  
  /**
   * @param int $level
   */
  function setLevel($level) {
    $this->level = (int) $level;
  }
  
  /**
   * @return string
   */
  function getSkillType() {
    if($this->skill instanceof SkillAttack) return "attack";
    elseif($this->skill instanceof SkillSpecial) return "special";
    else return "";
  }
  
  /**
   * @return bool
   */
  function canUse() {
    if($this->cooldown < 1) return true;
    else return false;
  }
  
  /**
   * @return void
   */
  function resetCooldown() {
    $this->cooldown = $this->skill->cooldown;
  }
  
  /**
   * @return void
   */
  function decreaseCooldown() {
    if($this->cooldown > 0) {
      $this->cooldown--;
    }
  }
}
?>