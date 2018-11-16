<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * GuildRank
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property OneHasMany|GuildPrivilege[] $privileges {1:m GuildPrivilege::$rank}
 * @property OneHasMany|Character[] $characters {1:m Character::$guildrank}
 */
final class GuildRank extends \Nextras\Orm\Entity\Entity {
  public function __toString() {
    return (string) $this->id;
  }
}
?>