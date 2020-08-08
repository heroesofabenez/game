<?php
declare(strict_types=1);

namespace HeroesofAbenez\Orm;

/**
 * GuildDonation
 *
 * @author Jakub Konečný
 * @property int $id {primary}
 * @property Character $character {m:1 Character::$guildDonations}
 * @property Guild $guild {m:1 Guild::$donations}
 * @property int $amount {default 0}
 * @property \DateTimeImmutable $when {default now}
 */
final class GuildDonation extends \Nextras\Orm\Entity\Entity {

}
?>