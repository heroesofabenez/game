<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Data structure for effect on character
 *
 * @author Jakub Konečný
 */
class CharacterEffect extends BaseEntity {
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
  
  /**
   * @param array $effect
   */
  function __construct(array $effect) {
    $types = ["buff", "debuff", "stun"];
    $sources = ["pet", "skill", "equipment"];
    if(!in_array($effect["type"], $types)) {
      exit("Invalid value for \$type passed to method CharacterEffect::__construct.");
    }
    if(!in_array($effect["source"], $sources)) {
      exit("Invalid value for \$source passed to method CharacterEffect::__construct.");
    }
    if(!in_array($effect["duration"], self::getDurations()) AND $effect["duration"] < 0) {
      exit("Invalid value for \$duration passed to method CharacterEffect::__construct.");
    }
    if($effect["type"] != "stun") {
      $stats = ["strength", "dexterity", "constitution", "intelligence", "charisma", "damage", "hit", "dodge", "initiative", "defense"];
      if(!is_int($effect["value"])) {
        exit("Invalid value for \$value passed to method CharacterEffect::__construct. Expected integer.");
      }
      if(!in_array($effect["stat"], $stats)) {
        exit("Invalid value for \$stat passed to method CharacterEffect::__construct.");
      }
      $this->stat = $effect["stat"];
      $this->value = $effect["value"];
    }
    $this->id = $effect["id"];
    $this->type = $effect["type"];
    $this->source = $effect["source"];
    $this->duration = $effect["duration"];
  }
  
  /**
   * @return string[]
   */
  static function getDurations(): array {
    return ["combat", "forever"];
  }
  
  /**
   * @param string|int $value
   * @throws \InvalidArgumentException
   */
  function setDuration($value) {
    if(!is_int($value) AND !in_array($value, self::getDurations())) {
      throw new \InvalidArgumentException("Invalid value set to CharacterEffect::\$duration. Expected string or integer.");
    }
    $this->duration = $value;
  }
}
?>