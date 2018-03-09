<?php
declare(strict_types=1);

namespace HeroesofAbenez\Entities;

use Nette\Localization\ITranslator;

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
  
  /** @var ITranslator */
  protected $translator;
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
  
  public function __construct(ITranslator $translator, string $action, bool $result, Character $character1, Character $character2, int $amount = 0, string $name = "") {
    $actions = ["attack", "skill_attack", "skill_special", "healing", "poison",];
    if(!in_array($action, $actions, true)) {
      exit("Invalid value for action passed to CombatAction::__construct.");
    }
    $this->translator = $translator;
    $this->action = $action;
    $this->result = $result;
    $this->amount = $amount;
    $this->character1 = $character1;
    $this->character2 = $character2;
    $this->name = $name;
    $this->parse();
  }
  
  public function getCharacter1(): Character {
    return $this->character1;
  }
  
  public function getCharacter2(): Character {
    return $this->character2;
  }
  
  public function getAction(): string {
    return $this->action;
  }
  
  public function getName(): string {
    return $this->name;
  }
  
  public function isResult(): bool {
    return $this->result;
  }
  
  public function getAmount(): int {
    return $this->amount;
  }
  
  public function getMessage(): string {
    return $this->message;
  }
  
  protected function parse(): void {
    $character1 = $this->character1->name;
    $character2 = $this->character2->name;
    $text = "";
    switch($this->action) {
      case "attack":
        if($this->result) {
          $text = $this->translator->translate("texts.combatLog.attackHits", $this->amount, ["character1" => $character1, "character2" => $character2]);
          if($this->character2->hitpoints < 1) {
            $text .= $this->translator->translate("texts.combatLog.characterFalls");
          }
        } else {
          $text = $this->translator->translate("texts.combatLog.attackFails", $this->amount, ["character1" => $character1, "character2" => $character2]);
        }
        break;
      case "skill_attack":
        if($this->result) {
          $text = $this->translator->translate("texts.combatLog.specialAttackHits", $this->amount, ["character1" => $character1, "character2" => $character2, "name" => $this->name]);
          if($this->character2->hitpoints < 1) {
            $text .= $this->translator->translate("texts.combatLog.characterFalls");
          }
        } else {
          $text = $this->translator->translate("texts.combatLog.specialAttackFails", $this->amount, ["character1" => $character1, "character2" => $character2, "name" => $this->name]);
        }
        break;
      case "skill_special":
        if($this->result) {
          $text = $this->translator->translate("texts.combatLog.specialSkillSuccess", 0, ["character1" => $character1, "character2" => $character2, "name" => $this->name]);
        } else {
          $text = $this->translator->translate("texts.combatLog.specialSKillFailure", 0, ["character1" => $character1, "character2" => $character2, "name" => $this->name]);
        }
        break;
      case "healing":
        if($this->result) {
          $text = $this->translator->translate("texts.combatLog.healingSuccess", $this->amount, ["character1" => $character1, "character2" => $character2]);
        } else {
          $text = $this->translator->translate("texts.combatLog.healingFailure", $this->amount, ["character1" => $character1, "character2" => $character2]);
        }
        break;
      case "poison":
        $text = $this->translator->translate("texts.combatLog.poison", $this->amount, ["character1" => $character1]);
        break;
    }
    $this->message =  $text;
  }
  
  public function __toString(): string {
    return $this->message;
  }
}
?>