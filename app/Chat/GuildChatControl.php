<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use HeroesofAbenez\Orm\Model as ORM;

/**
 * Guild Chat Control
 *
 * @author Jakub Konečný
 */
class GuildChatControl extends ChatControl {
  function __construct(ORM $orm, \Nette\Security\User $user, ChatCommandsProcessor  $processor) {
    $gid = $user->identity->guild;
    parent::__construct($orm, $user, $processor, "guild", $gid);
  }
}
?>