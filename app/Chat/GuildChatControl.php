<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * Guild Chat Control
 *
 * @author Jakub Konečný
 */
final class GuildChatControl extends ChatControl
{
    public function __construct(DatabaseAdapter $databaseAdapter, \Nette\Security\User $user)
    {
        $gid = $user->identity->guild;
        parent::__construct($databaseAdapter, "guild", $gid);
    }
}
