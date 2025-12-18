<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

/**
 * UserToCharacterMapper
 * Is responsible for mapping user to character (and vice versa)
 * Is used during authentication and registration
 *
 * @author Jakub Konečný
 */
interface UserToCharacterMapper
{
    public const USER_ID_NOT_LOGGED_IN = 0;
    public const USER_ID_NO_CHARACTER = -1;

    public function getRealId(): int;
}
