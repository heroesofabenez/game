<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use Nette\Localization\ITranslator;

/**
 * Guild Chat Control
 *
 * @author Jakub Konečný
 */
final class GuildChatControl extends ChatControl {
  public function __construct(IDatabaseAdapter $databaseAdapter, \Nette\Security\User $user, ITranslator $translator) {
    $gid = $user->identity->guild;
    parent::__construct($databaseAdapter, "guild", $gid, null, null, $translator);
  }
}
?>