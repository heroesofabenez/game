<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * SkillSpecialDummy
 *
 * @author Jakub Konečný
 * @property-read string $type
 * @property-read string|NULL $stat
 * @property-read int $value
 * @property-read int $valueGrowth
 * @property-read int $duration
 */
class SkillSpecialDummy extends BaseSkill {
  public const TYPE_BUFF = "buff";
  public const TYPE_DEBUFF = "debuff";
  public const TYPE_STUN = "stun";
  public const TYPE_POISON = "poison";
  public const TARGET_SELF = "self";
  public const TARGET_ENEMY = "enemy";
  public const TARGET_PARTY = "party";
  public const TARGET_ENEMY_PARTY = "enemy_party";
  public const STAT_HP = "hp";
  public const STAT_DAMAGE = "damage";
  public const STAT_DEFENSE = "defense";
  public const STAT_HIT = "hit";
  public const STAT_DODGE = "dodge";
  public const STAT_INITIATIVE = "initiative";
  /** @var string[] */
  public const NO_STAT_TYPES = [self::TYPE_STUN, self::TYPE_POISON,];
  
  /** @var string */
  protected $type;
  /** @var string|NULL */
  protected $stat;
  /** @var int */
  protected $value;
  /** @var int */
  protected $valueGrowth;
  /** @var int */
  protected $duration;
  
  public function __construct(SkillSpecial $skill) {
    $this->id = $skill->id;
    $this->name = $skill->name;
    $this->description = $skill->description;
    $this->neededClass = $skill->neededClass->id;
    $this->neededSpecialization = (!is_null($skill->neededSpecialization)) ? $skill->neededSpecialization->id : NULL;
    $this->neededLevel = $skill->neededLevel;
    $this->type = $skill->type;
    $this->target = $skill->target;
    $this->stat = $skill->stat;
    $this->value = $skill->value;
    $this->valueGrowth = $skill->valueGrowth;
    $this->levels = $skill->levels;
    $this->duration = $skill->duration;
  }
  
  public function getCooldown(): int {
    return 5;
  }
  
  public function getType(): string {
    return $this->type;
  }
  
  public function getStat(): ?string {
    return $this->stat;
  }
  
  public function getValue(): int {
    return $this->value;
  }
  
  public function getValueGrowth(): int {
    return $this->valueGrowth;
  }
  
  public function getDuration(): int {
    return $this->duration;
  }
}
?>