<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * Local Chat Control
 *
 * @author Jakub Konečný
 */
class LocalChatControl extends ChatControl {
  function __construct(\Nette\Database\Context $database, \Nette\Security\User $user, ChatCommandsProcessor  $processor) {
    $stage = $user->identity->stage;
    parent::__construct($database, $user, $processor, "stage", $stage, "current_stage");
  }
}
?>