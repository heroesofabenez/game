<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildRankCustom
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Guild $guild {m:1 Guild::$customRanks}
 * @property GuildRank $rank {m:1 GuildRank::$customNames}
 * @property string $name
 */
final class GuildRankCustom extends \Nextras\Orm\Entity\Entity {

}
?>