<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

use Nextras\Orm\Relationships\OneHasMany;

/**
 * Guild
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $name
 * @property string|NULL $description
 * @property int $money {default 0}
 * @property OneHasMany|GuildRankCustom[] $customRanks {1:m GuildRankCustom::$guild}
 */
class Guild extends \Nextras\Orm\Entity\Entity {
  
}
?>