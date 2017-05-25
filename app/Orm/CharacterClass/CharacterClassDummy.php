<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterClassDummy
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 * @property-read string $description
 * @property-read int $strength
 * @property-read float $strengthGrow
 * @property-read int $dexterity
 * @property-read float $dexterityGrow
 * @property-read int $constitution
 * @property-read float $constitutionGrow
 * @property-read int $intelligence
 * @property-read float $intelligenceGrow
 * @property-read int $charisma
 * @property-read float $charismaGrow
 * @property-read float $statPointsLevel
 * @property-read string $initiative
 */
class CharacterClassDummy {
  use \Nette\SmartObject;
  
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $description;
  /** @var int */
  protected $strength;
  /** @var float */
  protected $strengthGrow;
  /** @var int */
  protected $dexterity;
  /** @var float */
  protected $dexterityGrow;
  /** @var int */
  protected $constitution;
  /** @var float */
  protected $constitutionGrow;
  /** @var int */
  protected $intelligence;
  /** @var float */
  protected $intelligenceGrow;
  /** @var int */
  protected $charisma;
  /** @var float */
  protected $charismaGrow;
  /** @var float */
  protected $statPointsLevel;
  /** @var string */
  protected $initiative;
  
  function __construct(CharacterClass $class) {
    $this->id = $class->id;
    $this->name = $class->name;
    $this->description = $class->description;
    $this->strength = $class->strength;
    $this->strengthGrow = $class->strengthGrow;
    $this->dexterity = $class->dexterity;
    $this->dexterityGrow = $class->dexterityGrow;
    $this->constitution = $class->constitution;
    $this->charismaGrow = $class->charismaGrow;
    $this->intelligence = $class->intelligence;
    $this->intelligenceGrow = $class->intelligenceGrow;
    $this->charisma = $class->charisma;
    $this->charismaGrow = $class->charismaGrow;
    $this->statPointsLevel = $class->statPointsLevel;
    $this->initiative = $class->initiative;
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
   * @return int
   */
  function getStrength(): int {
    return $this->strength;
  }
  
  /**
   * @return float
   */
  function getStrengthGrow(): float {
    return $this->strengthGrow;
  }
  
  /**
   * @return int
   */
  function getDexterity(): int {
    return $this->dexterity;
  }
  
  /**
   * @return float
   */
  function getDexterityGrow(): float {
    return $this->dexterityGrow;
  }
  
  /**
   * @return int
   */
  function getConstitution(): int {
    return $this->constitution;
  }
  
  /**
   * @return float
   */
  function getConstitutionGrow(): float {
    return $this->constitutionGrow;
  }
  
  /**
   * @return int
   */
  function getIntelligence(): int {
    return $this->intelligence;
  }
  
  /**
   * @return float
   */
  function getIntelligenceGrow(): float {
    return $this->intelligenceGrow;
  }
  
  /**
   * @return int
   */
  function getCharisma(): int {
    return $this->charisma;
  }
  
  /**
   * @return float
   */
  function getCharismaGrow(): float {
    return $this->charismaGrow;
  }
  
  /**
   * @return float
   */
  function getStatPointsLevel(): float {
    return $this->statPointsLevel;
  }
  
  /**
   * @return string
   */
  function getInitiative(): string {
    return $this->initiative;
  }
}
?>