<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

/**
 * User to character mapper for development
 * Uses constant user id
 *
 * @author Jakub Konečný
 */
final class DevelopmentUserToCharacterMapper implements UserToCharacterMapper
{
    public const USER_ID = 1;

    public function getRealId(): int
    {
        return self::USER_ID;
    }
}
