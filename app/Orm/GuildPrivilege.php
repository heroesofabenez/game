<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildPrivilege
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property string $action
 * @property GuildRank $rank {m:1 GuildRank::$privileges}
 */
final class GuildPrivilege extends \Nextras\Orm\Entity\Entity
{
}
