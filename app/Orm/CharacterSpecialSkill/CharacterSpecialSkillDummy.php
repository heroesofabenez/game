<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Character skill special
 *
 * @author Jakub Konečný
 * @property-read SkillSpecialDummy $skill
 * @property-read int $value
 */
class CharacterSpecialSkillDummy extends BaseCharacterSkill {
  /** @var  SkillSpecialDummy */
  protected $skill;
  
  public function __construct(SkillSpecialDummy $skill, int $level) {
    parent::__construct($skill, $level);
  }
  
  public function getSkill(): SkillSpecialDummy {
    return $this->skill;
  }
  
  public function getValue(): int {
    if($this->skill->type === \HeroesofAbenez\Orm\SkillSpecialDummy::TYPE_STUN) {
      return 0;
    }
    $value = $this->skill->value;
    $value += $this->skill->valueGrowth * ($this->level - 1);
    return $value;
  }
}
?>