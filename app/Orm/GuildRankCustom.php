<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildRankCustom
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Guild $guild {m:1 Guild, oneSided=true}
 * @property GuildRank $rank {m:1 GuildRank, oneSided=true}
 * @property string $name
 */
final class GuildRankCustom extends \Nextras\Orm\Entity\Entity {

}
?>