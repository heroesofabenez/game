<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * Local Chat Control
 *
 * @author Jakub Konečný
 */
final class LocalChatControl extends ChatControl
{
    public function __construct(DatabaseAdapter $databaseAdapter, \Nette\Security\User $user)
    {
        $stage = $user->identity->stage;
        parent::__construct($databaseAdapter, "stage", $stage, "currentStage");
    }
}
