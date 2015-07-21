<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for effect on character
 *
 * @author Jakub Konečný
 */
class CharacterEffect extends BaseEntity {
  /** @var int */
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
    $types = array("buff", "debuff", "stun");
    $sources = array("pet", "skill", "equipment");
    $durations = array("combat", "forever");
    if(!in_array($effect["type"], $types)) exit("Invalid value for \$type passed to method CharacterEffect::__construct.");
    if(!in_array($effect["source"], $sources)) exit("Invalid value for \$source passed to method CharacterEffect::__construct.");
    if(!in_array($effect["duration"], $durations) AND $effect["duration"] < 0) exit("Invalid value for \$duration passed to method CharacterEffect::__construct.");
    if($effect["type"] === "stun") {
      
    } else {
      $stats = array("strength", "dexterity", "constitution", "intelligence", "charisma", "damage", "hit", "dodge", "initiative");
      if(!is_int($effect["value"])) exit("Invalid value for \$value passed to method CharacterEffect::__construct. Expected integer.");
      if(!in_array($effect["stat"], $stats)) exit("Invalid value for \$stat passed to method CharacterEffect::__construct.");
      $this->stat = $effect["stat"];
      $this->value = $effect["value"];
    }
    $this->id = $effect["id"];
    $this->type = $effect["type"];
    $this->source = $effect["source"];
    $this->duration = $effect["duration"];
  }
}
?>