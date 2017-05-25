<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use HeroesofAbenez\Orm\Model as ORM;

/**
 * Local Chat Control
 *
 * @author Jakub Konečný
 */
class LocalChatControl extends ChatControl {
  function __construct(ORM $orm, \Nette\Security\User $user, ChatCommandsProcessor  $processor) {
    $stage = $user->identity->stage;
    parent::__construct($orm, $user, $processor, "stage", $stage, "currentStage");
  }
}
?>