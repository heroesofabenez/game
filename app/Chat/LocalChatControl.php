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
  public function __construct(ORM $orm, \Nette\Security\User $user) {
    $stage = $user->identity->stage;
    parent::__construct($orm, $user, "stage", $stage, "currentStage");
  }
}
?>