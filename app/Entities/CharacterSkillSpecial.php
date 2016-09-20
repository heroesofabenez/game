<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Character skill special
 *
 * @author Jakub Konečný
 * @property-read int $value
 */
class CharacterSkillSpecial extends CharacterSkill {
  /**
   * @param \HeroesofAbenez\Entities\SkillSpecial $skill
   * @param int $level
   */
  function __construct(SkillSpecial $skill, $level) {
    parent::__construct($skill, $level);
  }
  
  /**
   * @return int
   */
  function getValue(): int {
    if($this->skill->type === "stun") return 0;
    $value = $this->skill->value;
    $value += (int) $this->skill->value_growth * ($this->level - 1);
    return $value;
  }
}
?>