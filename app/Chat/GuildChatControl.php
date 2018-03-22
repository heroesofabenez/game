<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * Guild Chat Control
 *
 * @author Jakub Konečný
 */
class GuildChatControl extends ChatControl {
  public function __construct(IDatabaseAdapter $databaseAdapter, \Nette\Security\User $user) {
    $gid = $user->identity->guild;
    parent::__construct($databaseAdapter, "guild", $gid);
  }
}
?>