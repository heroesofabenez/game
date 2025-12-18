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
 * @property string $description
 * @property int $money {default 0}
 * @property OneHasMany|Character[] $members {1:m Character::$guild, orderBy=[guildrank=DESC,id]}
 * @property OneHasMany|ChatMessage[] $chatMessages {1:m ChatMessage::$guild}
 * @property OneHasMany|GuildDonation[] $donations {1:m GuildDonation::$guild}
 * @property-read Character|null $leader {virtual}
 */
final class Guild extends \Nextras\Orm\Entity\Entity
{
    protected function getterLeader(): ?Character
    {
        return $this->members->toCollection()->limitBy(1)->getBy([ // @phpstan-ignore return.type
            "guildrank" => 7
        ]);
    }
}
