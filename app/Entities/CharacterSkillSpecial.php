<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Character skill special
 *
 * @author Jakub Konečný
 * @property-read SkillSpecial $skill
 * @property-read int $value
 */
class CharacterSkillSpecial extends CharacterSkill {
  /** @var  SkillSpecial */
  protected $skill;
  
  function __construct(SkillSpecial $skill, $level) {
    parent::__construct($skill, $level);
  }
  
  /**
   * @return SkillSpecial
   */
  function getSkill(): SkillSpecial {
    return $this->skill;
  }
  
  /**
   * @return int
   */
  function getValue(): int {
    if($this->skill->type === "stun") {
      return 0;
    }
    $value = $this->skill->value;
    $value += (int) $this->skill->value_growth * ($this->level - 1);
    return $value;
  }
}
?>