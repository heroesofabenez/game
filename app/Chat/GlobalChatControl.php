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
  public function __construct(ORM $orm, IDatabaseAdapter $databaseAdapter,  \Nette\Security\User $user) {
    /** @var \HeroesofAbenez\Orm\QuestStage $stage */
    $stage = $orm->stages->getById($user->identity->stage);
    $stagesIds = $orm->stages->findByArea($stage->area)->fetchPairs(NULL, "id");
    parent::__construct($databaseAdapter, "area", $stage->area->id, "currentStage", $stagesIds);
  }
}
?>