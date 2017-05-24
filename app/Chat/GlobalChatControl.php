<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat;

/**
 * Global Chat Control
 *
 * @author Jakub Konečný
 */
class GlobalChatControl extends ChatControl {
  function __construct(\Nette\Database\Context $database, \Nette\Security\User $user, \HeroesofAbenez\Model\Location $locationModel, ChatCommandsProcessor  $processor) {
    $stage = $locationModel->getStage($user->identity->stage);
    $stages = $locationModel->listOfStages($stage->area);
    $stagesIds = [];
    foreach($stages as $s) {
      $stagesIds[] = $s->id;
    }
    parent::__construct($database, $user, $processor, "area", $stage->area, "current_stage", $stagesIds);
  }
}
?>