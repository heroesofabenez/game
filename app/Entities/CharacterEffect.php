<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

use HeroesofAbenez\Orm\SkillSpecial,
    HeroesofAbenez\Utils\Constants;

/**
 * Data structure for effect on character
 *
 * @author Jakub Konečný
 * @property-read string $id
 * @property-read string $type
 * @property-read string $stat
 * @property-read int $value
 * @property-read string $source
 * @property int|string $duration
 */
class CharacterEffect {
  use \Nette\SmartObject;
  
  public const SOURCE_PET = "pet";
  public const SOURCE_SKILL = "skill";
  public const SOURCE_EQUIPMENT = "equipment";
  public const DURATION_COMBAT = "combat";
  public const DURATION_FOREVER = "forever";
  
  /** @var string */
  protected $id;
  /** @var string */
  protected $type;
  /** @var string */
  protected $stat = "";
  /** @var int */
  protected $value = 0;
  /** @var string */
  protected $source;
  /** @var int|string */
  protected $duration;
  
  public function __construct(array $effect) {
    if(!in_array($effect["type"], $this->getAllowedTypes(), true)) {
      exit("Invalid value for \$type passed to method CharacterEffect::__construct.");
    }
    if(!in_array($effect["source"], $this->getAllowedSources(), true)) {
      exit("Invalid value for \$source passed to method CharacterEffect::__construct.");
    }
    if(!in_array($effect["duration"], self::getDurations(), true) AND $effect["duration"] < 0) {
      exit("Invalid value for \$duration passed to method CharacterEffect::__construct.");
    }
    if(!in_array($effect["type"], SkillSpecial::NO_STAT_TYPES, true)) {
      $stats = ["strength", "dexterity", "constitution", "intelligence", "charisma", "damage", "hit", "dodge", "initiative", "defense"];
      if(!is_int($effect["value"])) {
        exit("Invalid value for \$value passed to method CharacterEffect::__construct. Expected integer.");
      }
      if(!in_array($effect["stat"], $stats, true) OR (is_null($effect["stat"]))) {
        exit("Invalid value for \$stat passed to method CharacterEffect::__construct.");
      }
      $this->stat = $effect["stat"];
      $this->value = $effect["value"];
    }
    $this->value = (int) $effect["value"];
    $this->id = $effect["id"];
    $this->type = $effect["type"];
    $this->source = $effect["source"];
    $this->duration = $effect["duration"];
  }
  
  /**
   * @return string[]
   */
  protected function getAllowedSources(): array {
    return Constants::getConstantsValues(static::class, "SOURCE_");
  }
  
  /**
   * @return string[]
   */
  protected function getAllowedTypes(): array {
    return Constants::getConstantsValues(SkillSpecial::class, "TYPE_");
  }
  
  /**
   * @return string[]
   */
  public static function getDurations(): array {
    return Constants::getConstantsValues(static::class, "DURATION_");
  }
  
  public function getId(): string {
    return $this->id;
  }
  
  public function getType(): string {
    return $this->type;
  }
  
  public function getStat(): string {
    return $this->stat;
  }
  
  public function getValue(): int {
    return $this->value;
  }
  
  public function getSource(): string {
    return $this->source;
  }
  
  /**
   * @return int|string
   */
  public function getDuration() {
    return $this->duration;
  }
  
  /**
   * @param string|int $value
   * @throws \InvalidArgumentException
   */
  public function setDuration($value) {
    if(!is_int($value) AND !in_array($value, self::getDurations(), true)) {
      throw new \InvalidArgumentException("Invalid value set to CharacterEffect::\$duration. Expected string or integer.");
    }
    $this->duration = $value;
  }
}
?>