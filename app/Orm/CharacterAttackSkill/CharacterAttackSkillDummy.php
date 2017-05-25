<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Character skill attack
 *
 * @author Jakub Konečný
 * @property-read SkillAttackDummy $skill
 * @property-read int $damage
 * @property-read int $hitRate
 */
class CharacterAttackSkillDummy extends BaseCharacterSkill {
  /** @var SkillAttackDummy */
  protected $skill;
  
  function __construct(SkillAttackDummy $skill, int $level) {
    parent::__construct($skill, $level);
  }
  
  /**
   * @return SkillAttackDummy
   */
  function getSkill(): SkillAttackDummy {
    return $this->skill;
  }
  
  /**
   * @return int
   */
  function getDamage(): int {
    $damage = 0;
    if(substr($this->skill->baseDamage, -1) === "%") {
      $damage += (int) $this->skill->baseDamage;
    }
    if(substr($this->skill->damageGrowth, -1) === "%") {
      $damage += (int) $this->skill->damageGrowth * ($this->level - 1);
    }
    return $damage;
  }
  
  /**
   * @return int
   */
  function getHitRate(): int {
    if(is_null($this->skill->hitRate)) {
      return 100;
    } elseif(substr($this->skill->hitRate, -1) === "%") {
      return (int) $this->skill->hitRate;
    } else {
      return 100;
    }
  }
}
?>