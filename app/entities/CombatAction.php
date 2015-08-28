<?php
namespace HeroesofAbenez\Entities;

/**
 * Data structure for combat action
 *
 * @author Jakub Konečný
 */
class CombatAction extends BaseEntity {
  /** @var \HeroesofAbenez\Entities\Character */
  protected $character1;
  /** @var \HeroesofAbenez\Entities\Character */
  protected $character2;
  /** @var  string */
  protected $action;
  /** @var string */
  protected $name;
  /** @var bool */
  protected $result;
  /** @var int */
  protected $amount;
  /** @var string */
  protected $message;
  
  /**
   * @param string $action
   * @param bool $result
   * @param \HeroesofAbenez\Entities\Character $character1
   * @param \HeroesofAbenez\Entities\Character $character2
   * @param int $amount
   * @param string $name
   */
  function __construct($action, $result, Character $character1, Character $character2, $amount = 0, $name = "") {
    $actions = array("attack", "skill_attack", "skill_special", "healing");
    if(!in_array($action, $actions)) exit("Invalid value for action passed to CombatAction::__construct.");
    $this->action = $action;
    $this->result = (bool) $result;
    $this->amount = (int) $amount;
    $this->character1 = $character1;
    $this->character2 = $character2;
    $this->name = (string) $name;
    $this->parse();
  }
  
  /**
   * @return void
   */
  protected function parse() {
    $text = $this->character1->name . " ";
    switch($this->action) {
case "attack":
case "skill_attack":
  if($this->action === "attack") $text .= "attacks {$this->character2->name} ";
  else $text .= "uses attack $this->name on {$this->character2->name} ";
  if($this->result) {
    $text .= " and hits. {$this->character2->name} loses $this->amount hitpoint(s).";
    if($this->character2->hitpoints < 1) $text .= " He/she falls on the ground.";
  } else {
    $text .= "but misses.";
  }
  break;
case "skill_special":
  if($this->result) $text .= "successfully casts $this->name on {$this->character2->name}";
  else $text .= "tries to cast $this->name on {$this->character2->name} but fails.";
  break;
case "healing":
  if($this->result) $text .= "heals {$this->character2->name} for $this->amount hitpoint(s).";
  else $text .= "tries to heal {$this->character2->name} but fails.";
  break;
    }
    $this->message =  $text;
  }
  
  /**
   * @return string
   */
  function __toString() {
    return $this->message;
  }
}
?>