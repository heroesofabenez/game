<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for effect on character
 *
 * @author Jakub Konečný
 */
class CharacterEffect extends BaseEntity {
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
  
  function __construct($type, $source, $duration, $stat = "", $value = "") {
    $types = array("buff", "debuff", "stun");
    $sources = array("pet", "skill", "equipment");
    $durations = array("combat", "forever");
    if(!in_array($type, $types)) exit("Invalid value for \$type passed to method CharacterEffect::__construct.");
    if(!in_array($source, $sources)) exit("Invalid value for \$source passed to method CharacterEffect::__construct.");
    if(!in_array($duration, $durations) AND $duration < 0) exit("Invalid value for \$duration passed to method CharacterEffect::__construct.");
    if($type === "stun") {
      
    } else {
      $stats = array("strength", "dexterity", "constitution", "intelligence", "charisma", "damage", "hit", "dodge", "initiative");
      if(!is_int($value)) exit("Invalid value for \$value passed to method CharacterEffect::__construct. Expected integer.");
      if(!in_array($stat, $stats)) exit("Invalid value for \$stat passed to method CharacterEffect::__construct.");
      $this->stat = $stat;
      $this->value = $value;
    }
    $this->type = $type;
    $this->source = $source;
    $this->duration = $duration;
  }
}
?>