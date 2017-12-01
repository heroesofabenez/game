<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * SkillAttackDummy
 *
 * @author Jakub Konečný
 * @property-read string $baseDamage
 * @property-read string $damageGrowth
 * @property-read int $strikes
 * @property-read string|NULL $hitRate
 */
class SkillAttackDummy extends BaseSkill {
  /** @var string */
  protected $baseDamage;
  /** @var string */
  protected $damageGrowth;
  /** @var int */
  protected $strikes;
  /** @var string|NULL */
  protected $hitRate;
  
  public function __construct(SkillAttack $skill) {
    $this->id = $skill->id;
    $this->name = $skill->name;
    $this->description = $skill->description;
    $this->neededClass = $skill->neededClass->id;
    $this->neededSpecialization = (!is_null($skill->neededSpecialization)) ? $skill->neededSpecialization->id : NULL;
    $this->neededLevel = $skill->neededLevel;
    $this->baseDamage = $skill->baseDamage;
    $this->damageGrowth = $skill->damageGrowth;
    $this->levels = $skill->levels;
    $this->target = $skill->target;
    $this->strikes = $skill->strikes;
    $this->hitRate = $skill->hitRate;
  }
  
  public function getCooldown(): int {
    return 3;
  }
  
  public function getBaseDamage(): string {
    return $this->baseDamage;
  }
  
  public function getDamageGrowth(): string {
    return $this->damageGrowth;
  }
  
  public function getStrikes(): int {
    return $this->strikes;
  }
  
  public function getHitRate(): ?string {
    return $this->hitRate;
  }
  
  
}
?>