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
  
  function __construct(SkillAttack $skill) {
    $this->id = $skill->id;
    $this->name = $skill->name;
    $this->description = $skill->description;
    $this->neededClass = $skill->neededClass->id;
    $this->neededSpecialization = ($skill->neededSpecialization) ? $skill->neededSpecialization->id : NULL;
    $this->neededLevel = $skill->neededLevel;
    $this->baseDamage = $skill->baseDamage;
    $this->damageGrowth = $skill->damageGrowth;
    $this->levels = $skill->levels;
    $this->target = $skill->target;
    $this->strikes = $skill->strikes;
    $this->hitRate = $skill->hitRate;
  }
  
  function getCooldown(): int {
    return 3;
  }
  
  function getBaseDamage(): string {
    return $this->baseDamage;
  }
  
  function getDamageGrowth(): string {
    return $this->damageGrowth;
  }
  
  function getStrikes(): int {
    return $this->strikes;
  }
  
  function getHitRate(): ?string {
    return $this->hitRate;
  }
  
  
}
?>