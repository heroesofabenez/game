<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nette\Localization\ITranslator;
use Nextras\Orm\Relationships\OneHasMany;

/**
 * GuildRank
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property-read string $nameT {virtual}
 * @property OneHasMany|GuildPrivilege[] $privileges {1:m GuildPrivilege::$rank}
 * @property OneHasMany|Character[] $characters {1:m Character::$guildrank}
 */
final class GuildRank extends \Nextras\Orm\Entity\Entity {
  private ITranslator $translator;

  public function injectTranslator(ITranslator $translator): void {
    $this->translator = $translator;
  }

  protected function getterNameT(): string {
    return $this->translator->translate("guildranks.$this->id.name");
  }
}
?>