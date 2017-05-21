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
 * @property OneHasMany|GuildRankCustom[] $customNames {1:m GuildRankCustom::$rank}
 */
class GuildRank extends \Nextras\Orm\Entity\Entity {
  function __toString() {
    return (string) $this->id;
  }
}
?>