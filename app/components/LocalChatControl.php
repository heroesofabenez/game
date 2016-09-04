<?php
namespace HeroesofAbenez\Chat;

use \HeroesofAbenez\Model\ChatCommandsProcessor;

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

interface LocalChatControlFactory {
  /** @return \HeroesofAbenez\Chat\LocalChatControl */
  function create();
}
?>