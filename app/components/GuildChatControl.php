<?php
namespace HeroesofAbenez\Chat;

use \HeroesofAbenez\Model\ChatCommandsProcessor;

/**
 * Guild Chat Control
 *
 * @author Jakub Konečný
 */
class GuildChatControl extends ChatControl {
  /**
   * @param \Nette\Database\Context $database
   * @param \Nette\Security\User $user
   */
  function __construct(\Nette\Database\Context $database, \Nette\Security\User $user, ChatCommandsProcessor  $processor) {
    $gid = $user->identity->guild;
    parent::__construct($database, $user, $processor, "chat_guild", "guild", $gid);
  }
}

interface GuildChatControlFactory {
  /** @return \HeroesofAbenez\Chat\GuildChatControl */
  function create();
}
?>