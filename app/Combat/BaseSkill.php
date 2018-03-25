<?php
declare(strict_types=1);

namespace HeroesofAbenez\Combat;

use Symfony\Component\OptionsResolver\OptionsResolver,
    Nexendrie\Utils\Constants;

/**
 * Base Skill
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read string $name
 * @property-read string $description
 * @property-read int $neededClass
 * @property-read int|NULL $neededSpecialization
 * @property-read int $neededLevel
 * @property-read string $target
 * @property-read int $levels
 * @property-read int $cooldown
 */
abstract class BaseSkill {
  use \Nette\SmartObject;
  
  /** @var int */
  protected $id;
  /** @var string */
  protected $name;
  /** @var string */
  protected $description;
  /** @var int */
  protected $neededClass;
  /** @var int|NULL */
  protected $neededSpecialization;
  /** @var int */
  protected $neededLevel;
  /** @var string */
  protected $target;
  /** @var int */
  protected $levels;
  
  protected function configureOptions(OptionsResolver $resolver): void {
    $allStats = [
      "id", "name", "description", "neededClass", "neededSpecialization", "neededLevel", "target", "levels",
    ];
    $resolver->setRequired($allStats);
    $resolver->setAllowedTypes("id", "int");
    $resolver->setAllowedTypes("name", "string");
    $resolver->setAllowedTypes("description", "string");
    $resolver->setAllowedTypes("neededClass", "integer");
    $resolver->setAllowedTypes("neededSpecialization", ["integer", "null"]);
    $resolver->setAllowedTypes("neededLevel", "integer");
    $resolver->setAllowedTypes("target", "string");
    $resolver->setAllowedValues("target", function(string $value) {
      return in_array($value, $this->getAllowedTargets(), true);
    });
    $resolver->setAllowedTypes("levels", "integer");
    $resolver->setAllowedValues("levels", function(int $value) {
      return ($value > 0);
    });
  }
  
  protected function getAllowedTargets(): array {
    return Constants::getConstantsValues(static::class, "TARGET_");
  }
  
  abstract public function getCooldown(): int;
  
  public function getId(): int {
    return $this->id;
  }
  
  public function getName(): string {
    return $this->name;
  }
  
  public function getDescription(): string {
    return $this->description;
  }
  
  public function getNeededClass(): int {
    return $this->neededClass;
  }
  
  public function getNeededSpecialization(): ?int {
    return $this->neededSpecialization;
  }
  
  public function getNeededLevel(): int {
    return $this->neededLevel;
  }
  
  public function getTarget(): string {
    return $this->target;
  }
  
  public function getLevels(): int {
    return $this->levels;
  }
}
?>