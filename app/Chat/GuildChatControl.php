<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

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
?>