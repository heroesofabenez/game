<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use Nette\Localization\ITranslator;

/**
 * Local Chat Control
 *
 * @author Jakub Konečný
 */
final class LocalChatControl extends ChatControl {
  public function __construct(IDatabaseAdapter $databaseAdapter, \Nette\Security\User $user, ITranslator $translator) {
    $stage = $user->identity->stage;
    parent::__construct($databaseAdapter, "stage", $stage, "currentStage", null, $translator);
  }
}
?>