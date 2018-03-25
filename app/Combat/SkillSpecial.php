<?php
declare(strict_types=1);

namespace HeroesofAbenez\Combat;

use Symfony\Component\OptionsResolver\OptionsResolver,
    Nexendrie\Utils\Constants;

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
class SkillSpecial extends BaseSkill {
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
    $this->type = $data["type"];
    $this->target = $data["target"];
    $this->stat = $data["stat"];
    $this->value = $data["value"];
    $this->valueGrowth = $data["valueGrowth"];
    $this->levels = $data["levels"];
    $this->duration = $data["duration"];
  }
  
  protected function configureOptions(OptionsResolver $resolver): void {
    parent::configureOptions($resolver);
    $allStats = ["type", "stat", "value", "valueGrowth", "duration",];
    $resolver->setRequired($allStats);
    $resolver->setAllowedTypes("type", "string");
    $resolver->setAllowedValues("type", function(string $value) {
      return in_array($value, $this->getAllowedTypes(), true);
    });
    $resolver->setAllowedTypes("stat", ["string", "null"]);
    $resolver->setAllowedValues("stat", function(string $value) {
      return is_null($value) OR in_array($value, $this->getAllowedStats(), true);
    });
    $resolver->setAllowedTypes("value", "integer");
    $resolver->setAllowedValues("value", function(int $value) {
      return ($value >= 0);
    });
    $resolver->setAllowedTypes("valueGrowth", "integer");
    $resolver->setAllowedValues("valueGrowth", function(int $value) {
      return ($value >= 0);
    });
    $resolver->setAllowedTypes("duration", "integer");
    $resolver->setAllowedValues("duration", function(int $value) {
      return ($value >= 0);
    });
  }
  
  protected function getAllowedTypes(): array {
    return Constants::getConstantsValues(static::class, "TYPE_");
  }
  
  protected function getAllowedStats(): array {
    return Constants::getConstantsValues(static::class, "STAT_");
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