<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * Local Chat Control
 *
 * @author Jakub Konečný
 */
class LocalChatControl extends ChatControl {
  /**
   * @param \Nette\Database\Context $database
   * @param \Nette\Security\User $user
   */
  function __construct(\Nette\Database\Context $database, \Nette\Security\User $user, ChatCommandsProcessor  $processor) {
    $stage = $user->identity->stage;
    parent::__construct($database, $user, $processor, "chat_local", "stage", $stage, "current_stage");
  }
}
?>