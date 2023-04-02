<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nette\Localization\ITranslator;
use Nextras\Orm\Relationships\OneHasMany;

/**
 * CharacterSpecialization
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property-read  string $name {virtual}
 * @property CharacterClass $class {m:1 CharacterClass::$specializations}
 * @property float $strengthGrow
 * @property float $dexterityGrow
 * @property float $constitutionGrow
 * @property float $intelligenceGrow
 * @property float $charismaGrow
 * @property float $statPointsLevel
 * @property OneHasMany|Character[] $characters {1:m Character::$specialization}
 * @property OneHasMany|SkillAttack[] $attackSkills {1:m SkillAttack::$neededSpecialization}
 * @property OneHasMany|SkillSpecial[] $specialSkills {1:m SkillSpecial::$neededSpecialization}
 * @property OneHasMany|PveArenaOpponent[] $arenaNpcs {1:m PveArenaOpponent::$specialization}
 * @property OneHasMany|Npc[] $npcs {1:m Npc::$specialization}
 * @property OneHasMany|Item[] $items {1:m Item::$requiredSpecialization}
 * @property-read string $mainStat {virtual}
 */
final class CharacterSpecialization extends \Nextras\Orm\Entity\Entity {
  private ITranslator $translator;

  public function injectTranslator(ITranslator $translator): void {
    $this->translator = $translator;
  }

  protected function getterName(): string {
    return $this->translator->translate("subclasses.$this->id.name");
  }

  protected function getterMainStat(): string {
    $stats = [
      "strength" => $this->strengthGrow, "dexterity" => $this->dexterityGrow, "constitution" => $this->constitutionGrow,
      "intelligence" => $this->intelligenceGrow, "charisma" => $this->charismaGrow,
    ];
    return (string) array_search(max($stats), $stats, true);
  }
}
?>