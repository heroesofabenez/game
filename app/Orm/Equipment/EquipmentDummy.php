<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Data structure for equipment
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 * @property-read string $description
 * @property-read string $slot
 * @property-read string|NULL $type
 * @property-read int $requiredLevel
 * @property int|NULL $requiredClass
 * @property-read int $price
 * @property-read int $strength
 * @property-read int $durability
 * @property-read array $deployParams Deploy params of the equipment
 * @property bool $worn Is the item worn?
 */
class EquipmentDummy {
  use \Nette\SmartObject;
  
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $description;
  /** @var string */
  protected $slot;
  /** @var string|NULL */
  protected $type;
  /** @var int */
  protected $requiredLevel;
  /** @var int|NULL */
  protected $requiredClass;
  /** @var int */
  protected $price;
  /** @var int */
  protected $strength;
  /** @var int */
  protected $durability;
  /** @var bool */
  protected $worn = false;
  
  function __construct(Equipment $equipment) {
    $this->id = $equipment->id;
    $this->name = $equipment->name;
    $this->slot = $equipment->slot;
    $this->type = $equipment->type;
    $this->requiredLevel = $equipment->requiredLevel;
    $this->requiredClass = ($equipment->requiredClass) ? $equipment->requiredClass->id : NULL;
    $this->price = $equipment->price;
    $this->strength = $equipment->strength;
    $this->durability = $equipment->durability;
  }
  
  /**
   * @return int
   */
  function getId(): int {
    return $this->id;
  }
  
  /**
   * @return string
   */
  function getName(): string {
    return $this->name;
  }
  
  /**
   * @return string
   */
  function getDescription(): string {
    return $this->description;
  }
  
  /**
   * @return string
   */
  function getSlot(): string {
    return $this->slot;
  }
  
  /**
   * @return string|NULL
   */
  function getType(): ?string {
    return $this->type;
  }
  
  /**
   * @return int
   */
  function getRequiredLevel(): int {
    return $this->requiredLevel;
  }
  
  /**
   * @return int|NULL
   */
  function getRequiredClass(): ?int {
    return $this->requiredClass;
  }
  
  function setRequiredClass(int $class) {
    $this->requiredClass = $class;
  }
  
  /**
   * @return int
   */
  function getPrice(): int {
    return $this->price;
  }
  
  /**
   * @return int
   */
  function getStrength(): int {
    return $this->strength;
  }
  
  /**
   * @return int
   */
  function getDurability(): int {
    return $this->durability;
  }
  
  /**
   * Returns deploy parameters of the equipment (for effect to character)
   *
   * @return array params
   */
  function getDeployParams(): array {
    $stat = [
      "weapon" => "damage", "armor" => "defense", "shield" => "dodge", "amulet" => "initiative"
    ];
    $return = [
      "id" => "equipment" . $this->id . "bonusEffect",
      "type" => "buff",
      "stat" => $stat[$this->slot],
      "value" => $this->strength,
      "source" => "equipment",
      "duration" => "combat"
    ];
    return $return;
  }
  
  /**
   * @return bool
   */
  function isWorn(): bool {
    return $this->worn;
  }
  
  /**
   * @param bool $worn
   */
  function setWorn(bool $worn) {
    $this->worn = $worn;
  }
}
?>