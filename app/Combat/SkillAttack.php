<?php
declare(strict_types=1);

namespace HeroesofAbenez\Combat;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * SkillAttackDummy
 *
 * @author Jakub Konečný
 * @property-read string $baseDamage
 * @property-read string $damageGrowth
 * @property-read int $strikes
 * @property-read string|NULL $hitRate
 */
class SkillAttack extends BaseSkill {
  public const TARGET_SINGLE = "single";
  public const TARGET_ROW = "row";
  public const TARGET_COLUMN = "column";
  
  /** @var string */
  protected $baseDamage;
  /** @var string */
  protected $damageGrowth;
  /** @var int */
  protected $strikes;
  /** @var string|NULL */
  protected $hitRate;
  
  public function __construct(array $data) {
    $resolver = new OptionsResolver();
    $this->configureOptions($resolver);
    $data = $resolver->resolve($data);
    $this->id = $data["id"];
    $this->name = $data["name"];
    $this->description = $data["description"];
    $this->neededClass = $data["neededClass"];
    $this->neededSpecialization = $data["neededSpecialization"];
    $this->neededLevel = $data["neededLevel"];
    $this->baseDamage = $data["baseDamage"];
    $this->damageGrowth = $data["damageGrowth"];
    $this->levels = $data["levels"];
    $this->target = $data["target"];
    $this->strikes = $data["strikes"];
    $this->hitRate = $data["hitRate"];
  }
  
  protected function configureOptions(OptionsResolver $resolver): void {
    parent::configureOptions($resolver);
    $allStats = ["baseDamage", "damageGrowth", "strikes", "hitRate",];
    $resolver->setRequired($allStats);
    $resolver->setAllowedTypes("baseDamage", "string");
    $resolver->setAllowedTypes("damageGrowth", "string");
    $resolver->setAllowedTypes("strikes", "integer");
    $resolver->setAllowedValues("strikes", function(int $value) {
      return ($value > 0);
    });
    $resolver->setAllowedTypes("hitRate", ["string", "null"]);
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