<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

/**
 * User to character mapper for testing
 * Allows using any user id
 *
 * @author Jakub Konečný
 * @property int $realId
 */
final class TestingUserToCharacterMapper implements IUserToCharacterMapper
{
    use \Nette\SmartObject;

    public int $realId = DevelopmentUserToCharacterMapper::USER_ID;

    public function getRealId(): int
    {
        return $this->realId;
    }

    public function setRealId(int $realId): void
    {
        $this->realId = $realId;
    }
}
