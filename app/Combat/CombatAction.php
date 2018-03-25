<?php
declare(strict_types=1);

namespace HeroesofAbenez\Combat;

use Nette\Localization\ITranslator,
    Nexendrie\Utils\Constants,
    Symfony\Component\OptionsResolver\OptionsResolver;

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
  
  public const ACTION_ATTACK = "attack";
  public const ACTION_SKILL_ATTACK = "skill_attack";
  public const ACTION_SKILL_SPECIAL = "skill_special";
  public const ACTION_HEALING = "healing";
  public const ACTION_POISON = "poison";
  
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
  
  public function __construct(ITranslator $translator, array $action) {
    $requiredStats = ["action", "result", "character1", "character2",];
    $allStats = array_merge($requiredStats, ["amount", "name",]);
    $resolver = new OptionsResolver();
    $resolver->setDefined($allStats);
    $resolver->setRequired($requiredStats);
    $resolver->setAllowedTypes("action", "string");
    $resolver->setAllowedValues("action", function(string $value) {
      return in_array($value, $this->getAllowedActions(), true);
    });
    $resolver->setAllowedTypes("result", "bool");
    $resolver->setAllowedTypes("amount", "integer");
    $resolver->setDefault("amount", 0);
    $resolver->setAllowedTypes("name", "string");
    $resolver->setDefault("name", "");
    $resolver->setAllowedTypes("character1", Character::class);
    $resolver->setAllowedTypes("character2", Character::class);
    $action = $resolver->resolve($action);
    $this->translator = $translator;
    $this->action = $action["action"];
    $this->result = $action["result"];
    $this->amount = $action["amount"];
    $this->character1 = $action["character1"];
    $this->character2 = $action["character2"];
    $this->name = $action["name"];
    $this->parse();
  }
  
  /**
   * @return string[]
   */
  protected function getAllowedActions(): array {
    return Constants::getConstantsValues(static::class, "ACTION_");
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
      case static::ACTION_ATTACK:
        if($this->result) {
          $text = $this->translator->translate("combat.log.attackHits", $this->amount, ["character1" => $character1, "character2" => $character2]);
          if($this->character2->hitpoints < 1) {
            $text .= $this->translator->translate("combat.log.characterFalls");
          }
        } else {
          $text = $this->translator->translate("combat.log.attackFails", $this->amount, ["character1" => $character1, "character2" => $character2]);
        }
        break;
      case static::ACTION_SKILL_ATTACK:
        if($this->result) {
          $text = $this->translator->translate("combat.log.specialAttackHits", $this->amount, ["character1" => $character1, "character2" => $character2, "name" => $this->name]);
          if($this->character2->hitpoints < 1) {
            $text .= $this->translator->translate("combat.log.characterFalls");
          }
        } else {
          $text = $this->translator->translate("combat.log.specialAttackFails", $this->amount, ["character1" => $character1, "character2" => $character2, "name" => $this->name]);
        }
        break;
      case static::ACTION_SKILL_SPECIAL:
        if($this->result) {
          $text = $this->translator->translate("combat.log.specialSkillSuccess", 0, ["character1" => $character1, "character2" => $character2, "name" => $this->name]);
        } else {
          $text = $this->translator->translate("combat.log.specialSKillFailure", 0, ["character1" => $character1, "character2" => $character2, "name" => $this->name]);
        }
        break;
      case static::ACTION_HEALING:
        if($this->result) {
          $text = $this->translator->translate("combat.log.healingSuccess", $this->amount, ["character1" => $character1, "character2" => $character2]);
        } else {
          $text = $this->translator->translate("combat.log.healingFailure", $this->amount, ["character1" => $character1, "character2" => $character2]);
        }
        break;
      case static::ACTION_POISON:
        $text = $this->translator->translate("combat.log.poison", $this->amount, ["character1" => $character1]);
        break;
    }
    $this->message =  $text;
  }
  
  public function __toString(): string {
    return $this->message;
  }
}
?>