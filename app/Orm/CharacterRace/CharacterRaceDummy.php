<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * Data structure for race
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 * @property-read string $description
 * @property-read int $strength
 * @property-read int $dexterity
 * @property-read int $constitution
 * @property-read int $intelligence
 * @property-read int $charisma
 */
class CharacterRaceDummy {
  use \Nette\SmartObject;
  
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $description;
  /** @var int */
  protected $strength;
  /** @var int */
  protected $dexterity;
  /** @var int */
  protected $constitution;
  /** @var int */
  protected $intelligence;
  /** @var int */
  protected $charisma;
  
  function __construct(CharacterRace $race) {
    $this->id = $race->id;
    $this->name = $race->name;
    $this->description = $race->description;
    $this->strength = $race->strength;
    $this->dexterity = $race->dexterity;
    $this->constitution = $race->constitution;
    $this->intelligence = $race->intelligence;
    $this->charisma = $race->charisma;
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
   * @return int
   */
  function getDexterity(): int {
    return $this->dexterity;
  }
  
  /**
   * @return int
   */
  function getConstitution(): int {
    return $this->constitution;
  }
  
  /**
   * @return int
   */
  function getIntelligence(): int {
    return $this->intelligence;
  }
  
  /**
   * @return int
   */
  function getCharisma(): int {
    return $this->charisma;
  }
}
?>