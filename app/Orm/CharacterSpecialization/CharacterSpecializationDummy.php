<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * CharacterSpecializationDummy
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 * @property-read int $class
 * @property-read float $strengthGrow
 * @property-read float $dexterityGrow
 * @property-read float $constitutionGrow
 * @property-read float $intelligenceGrow
 * @property-read float $charismaGrow
 * @property-read float $statPointsLevel
 */
class CharacterSpecializationDummy {
  use \Nette\SmartObject;
  
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var int */
  protected $class;
  /** @var float */
  protected $strengthGrow;
  /** @var float */
  protected $dexterityGrow;
  /** @var float */
  protected $constitutionGrow;
  /** @var float */
  protected $intelligenceGrow;
  /** @var float */
  protected $charismaGrow;
  /** @var float */
  protected $statPointsLevel;
  
  function __construct(CharacterSpecialization $specialization) {
    $this->id = $specialization->id;
    $this->name = $specialization->name;
    $this->class = $specialization->class->id;
    $this->strengthGrow = $specialization->strengthGrow;
    $this->dexterityGrow = $specialization->dexterityGrow;
    $this->constitutionGrow = $specialization->constitutionGrow;
    $this->intelligenceGrow = $specialization->intelligenceGrow;
    $this->charismaGrow = $specialization->charismaGrow;
    $this->statPointsLevel = $specialization->statPointsLevel;
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
   * @return int
   */
  function getClass(): int {
    return $this->class;
  }
  
  /**
   * @return float
   */
  function getStrengthGrow(): float {
    return $this->strengthGrow;
  }
  
  /**
   * @return float
   */
  function getDexterityGrow(): float {
    return $this->dexterityGrow;
  }
  
  /**
   * @return float
   */
  function getConstitutionGrow(): float {
    return $this->constitutionGrow;
  }
  
  /**
   * @return float
   */
  function getIntelligenceGrow(): float {
    return $this->intelligenceGrow;
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
}
?>