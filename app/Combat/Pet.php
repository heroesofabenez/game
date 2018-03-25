<?php
declare(strict_types=1);

namespace HeroesofAbenez\Combat;

use Symfony\Component\OptionsResolver\OptionsResolver,
    Nexendrie\Utils\Constants;

/**
 * Pet
 *
 * @author Jakub Konečný
 * @property-read int $id
 * @property-read bool $deployed
 * @property-read string $bonusStat
 * @property-read int $bonusValue
 * @property-read array $deployParams
 */
class Pet {
  use \Nette\SmartObject;
  
  public const STAT_STRENGTH = "strength";
  public const STAT_DEXTERITY = "dexterity";
  public const STAT_CONSTITUTION = "constitution";
  public const STAT_INTELLIGENCE = "intelligence";
  public const STAT_CHARISMA = "charisma";
  
  /** @var int */
  protected $id;
  /** @var bool */
  protected $deployed;
  /** @var string */
  protected $bonusStat;
  /** @var int */
  protected $bonusValue;
  
  public function __construct(array $data) {
    $allStats = ["id", "deployed", "bonusStat", "bonusValue",];
    $resolver = new OptionsResolver();
    $resolver->setDefined($allStats);
    $resolver->setRequired($allStats);
    $resolver->setAllowedTypes("id", "integer");
    $resolver->setAllowedTypes("deployed", "boolean");
    $resolver->setAllowedTypes("bonusStat", "string");
    $resolver->setAllowedValues("bonusStat", function(string $value) {
      return in_array($value, $this->getAllowedStats(), true);
    });
    $resolver->setAllowedTypes("bonusValue", "integer");
    $resolver->setAllowedValues("bonusValue", function(int $value) {
      return ($value >= 0);
    });
    $data = $resolver->resolve($data);
    $this->id = $data["id"];
    $this->deployed = $data["deployed"];
    $this->bonusStat = $data["bonusStat"];
    $this->bonusValue = $data["bonusValue"];
  }
  
  protected function getAllowedStats(): array {
    return Constants::getConstantsValues(static::class, "STAT_");
  }
  
  public function getId(): int {
    return $this->id;
  }
  
  public function isDeployed(): bool {
    return $this->deployed;
  }
  
  public function getBonusStat(): string {
    return $this->bonusStat;
  }
  
  public function getBonusValue(): int {
    return $this->bonusValue;
  }
  
  public function getDeployParams(): array {
    return [
      "id" => "pet" . $this->id . "bonusEffect",
      "type" => "buff",
      "stat" => $this->bonusStat,
      "value" => $this->bonusValue,
      "source" => CharacterEffect::SOURCE_PET,
      "duration" => CharacterEffect::DURATION_COMBAT,
    ];
  }
}
?>