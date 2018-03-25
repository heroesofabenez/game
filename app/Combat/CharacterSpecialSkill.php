<?php
declare(strict_types=1);

namespace HeroesofAbenez\Combat;

/**
 * Character skill special
 *
 * @author Jakub Konečný
 * @property-read SkillSpecial $skill
 * @property-read int $value
 */
class CharacterSpecialSkill extends BaseCharacterSkill {
  /** @var SkillSpecial */
  protected $skill;
  
  public function __construct(SkillSpecial $skill, int $level) {
    parent::__construct($skill, $level);
  }
  
  public function getSkill(): SkillSpecial {
    return $this->skill;
  }
  
  public function getValue(): int {
    if($this->skill->type === \HeroesofAbenez\Combat\SkillSpecial::TYPE_STUN) {
      return 0;
    }
    $value = $this->skill->value;
    $value += $this->skill->valueGrowth * ($this->level - 1);
    return $value;
  }
}
?>