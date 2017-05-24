<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

/**
 * Data structure for combat action
 *
 * @author Jakub Konečný
 * @property-read Character $character1
 * @property-read Character $character2
 * @property-read string $action
 * @property-read string $name
 * @property-read bool $result
 * @property-read int $amount
 * @property-read string $message
 */
class CombatAction {
  use \Nette\SmartObject;
  
  /** @var Character */
  protected $character1;
  /** @var Character */
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
  
  function __construct(string $action, bool $result, Character $character1, Character $character2, int $amount = 0, string $name = "") {
    $actions = ["attack", "skill_attack", "skill_special", "healing"];
    if(!in_array($action, $actions)) {
      exit("Invalid value for action passed to CombatAction::__construct.");
    }
    $this->action = $action;
    $this->result = $result;
    $this->amount = $amount;
    $this->character1 = $character1;
    $this->character2 = $character2;
    $this->name = $name;
    $this->parse();
  }
  
  /**
   * @return Character
   */
  function getCharacter1(): Character {
    return $this->character1;
  }
  
  /**
   * @return Character
   */
  function getCharacter2(): Character {
    return $this->character2;
  }
  
  /**
   * @return string
   */
  function getAction(): string {
    return $this->action;
  }
  
  /**
   * @return string
   */
  function getName(): string {
    return $this->name;
  }
  
  /**
   * @return bool
   */
  function isResult(): bool {
    return $this->result;
  }
  
  /**
   * @return int
   */
  function getAmount(): int {
    return $this->amount;
  }
  
  /**
   * @return string
   */
  function getMessage(): string {
    return $this->message;
  }
  
  /**
   * @return void
   */
  protected function parse(): void {
    $text = $this->character1->name . " ";
    switch($this->action) {
      case "attack":
      case "skill_attack":
        if($this->action === "attack") {
          $text .= "attacks {$this->character2->name} ";
        } else {
          $text .= "uses attack $this->name on {$this->character2->name} ";
        }
        if($this->result) {
          $text .= " and hits. {$this->character2->name} loses $this->amount hitpoint(s).";
          if($this->character2->hitpoints < 1) {
            $text .= " He/she falls on the ground.";
          }
        } else {
          $text .= "but misses.";
        }
        break;
      case "skill_special":
        if($this->result) {
          $text .= "successfully casts $this->name on {$this->character2->name}.";
        } else {
          $text .= "tries to cast $this->name on {$this->character2->name} but fails.";
        }
        break;
      case "healing":
        if($this->result) {
          $text .= "heals {$this->character2->name} for $this->amount hitpoint(s).";
        } else {
          $text .= "tries to heal {$this->character2->name} but fails.";
        }
        break;
    }
    $this->message =  $text;
  }
  
  /**
   * @return string
   */
  function __toString(): string {
    return $this->message;
  }
}
?>