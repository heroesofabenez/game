<?php
namespace HeroesofAbenez\Entities;

/**
 * Base character skill
 *
 * @author Jakub Konečný
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