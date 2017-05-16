<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Character skill attack
 *
 * @author Jakub Konečný
 * @property-read SkillAttack $skill
 * @property-read int $damage
 * @property-read int $hitRate
 */
class CharacterSkillAttack extends CharacterSkill {
  /** @var SkillAttack */
  protected $skill;
  
  function __construct(SkillAttack $skill, int $level) {
    parent::__construct($skill, $level);
  }
  
  /**
   * @return SkillAttack
   */
  function getSkill(): SkillAttack {
    return $this->skill;
  }
  
  /**
   * @return int
   */
  function getDamage(): int {
    $damage = 0;
    if(substr($this->skill->base_damage, -1) === "%") {
      $damage += (int) $this->skill->base_damage;
    }
    if(substr($this->skill->damage_growth, -1) === "%") {
      $damage += (int) $this->skill->damage_growth * ($this->level - 1);
    }
    return $damage;
  }
  
  /**
   * @return int
   */
  function getHitRate(): int {
    if(is_null($this->skill->hit_rate)) {
      return 100;
    } elseif(substr($this->skill->hit_rate, -1) === "%") {
      return (int) $this->skill->hit_rate;
    } else {
      return 100;
    }
  }
}
?>