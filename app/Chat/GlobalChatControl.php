<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

use HeroesofAbenez\Orm\Model as ORM;

/**
 * Global Chat Control
 *
 * @author Jakub Konečný
 */
class GlobalChatControl extends ChatControl {
  function __construct(ORM $orm, \Nette\Security\User $user, ChatCommandsProcessor  $processor) {
    $stage = $orm->stages->getById($user->identity->stage);
    $stagesIds = $orm->stages->findByArea($stage->area)->fetchPairs(NULL, "id");
    parent::__construct($orm, $user, $processor, "area", $stage->area->id, "currentStage", $stagesIds);
  }
}
?>