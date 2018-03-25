<?php
declare(strict_types=1);

namespace HeroesofAbenez\Combat;

use Symfony\Component\OptionsResolver\OptionsResolver,
    Nexendrie\Utils\Constants;

/**
 * Equipment
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 * @property-read string $slot
 * @property-read string|NULL $type
 * @property-read int $strength
 * @property-read array $deployParams Deploy params of the equipment
 * @property-read bool $worn Is the item worn?
 */
class Equipment {
  use \Nette\SmartObject;
  
  public const SLOT_WEAPON = "weapon";
  public const SLOT_ARMOR = "armor";
  public const SLOT_SHIELD = "shield";
  public const SLOT_AMULET = "amulet";
  public const TYPE_SWORD = "sword";
  public const TYPE_AXE = "axe";
  public const TYPE_CLUB = "club";
  public const TYPE_DAGGER = "dagger";
  public const TYPE_SPEAR = "spear";
  public const TYPE_STAFF = "staff";
  public const TYPE_BOW = "bow";
  public const TYPE_CROSSBOW = "crossbow";
  public const TYPE_THROWING_KNIFE = "throwing knife";
  
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $slot;
  /** @var string|NULL */
  protected $type;
  /** @var int */
  protected $strength;
  /** @var bool */
  protected $worn;
  
  public function __construct(array $data) {
    $allStats = ["id", "name", "slot", "type", "strength", "worn",];
    $resolver = new OptionsResolver();
    $resolver->setDefined($allStats);
    $resolver->setRequired($allStats);
    $resolver->setAllowedTypes("id", "integer");
    $resolver->setAllowedTypes("name", "string");
    $resolver->setAllowedTypes("slot", "string");
    $resolver->setAllowedValues("slot", function(string $value) {
      return in_array($value, $this->getAllowedSlots(), true);
    });
    $resolver->setAllowedTypes("type", ["string", "null"]);
    $resolver->setAllowedValues("type", function(?string $value) {
      return is_null($value) OR in_array($value, $this->getAllowedTypes(), true);
    });
    $resolver->setAllowedTypes("strength", "integer");
    $resolver->setAllowedValues("strength", function(int $value) {
      return ($value >= 0);
    });
    $resolver->setAllowedTypes("worn", "boolean");
    $data = $resolver->resolve($data);
    $this->id = $data["id"];
    $this->name = $data["name"];
    $this->slot = $data["slot"];
    $this->type = $data["type"];
    $this->strength = $data["strength"];
    $this->worn = $data["worn"];
  }
  
  protected function getAllowedSlots(): array {
    return Constants::getConstantsValues(static::class, "SLOT_");
  }
  
  protected function getAllowedTypes(): array {
    return Constants::getConstantsValues(static::class, "TYPE_");
  }
  
  public function getId(): int {
    return $this->id;
  }
  
  public function getName(): string {
    return $this->name;
  }
  
  public function getSlot(): string {
    return $this->slot;
  }
  
  public function getType(): ?string {
    return $this->type;
  }
  
  public function getStrength(): int {
    return $this->strength;
  }
  
  public function isWorn(): bool {
    return $this->worn;
  }
  
  public function getDeployParams(): array {
    $stat = [
      static::SLOT_WEAPON => "damage", static::SLOT_ARMOR => "defense",
      static::SLOT_SHIELD => "dodge", static::SLOT_AMULET => "initiative",
    ];
    $return = [
      "id" => "equipment" . $this->id . "bonusEffect",
      "type" => "buff",
      "stat" => $stat[$this->slot],
      "value" => $this->strength,
      "source" => CharacterEffect::SOURCE_EQUIPMENT,
      "duration" => CharacterEffect::DURATION_COMBAT,
    ];
    return $return;
  }
}
?>