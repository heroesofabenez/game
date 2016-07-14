<?php
namespace HeroesofAbenez\Chat;

/**
 * Global Chat Control
 *
 * @author Jakub Konečný
 */
class GlobalChatControl extends ChatControl {
  /**
   * @param \Nette\Database\Context $database
   * @param \Nette\Security\User $user
   * @param \HeroesofAbenez\Model\Location $locationModel
   */
  function __construct(\Nette\Database\Context $database, \Nette\Security\User $user, \HeroesofAbenez\Model\Location $locationModel) {
    $stage = $locationModel->getStage($user->identity->stage);
    $stages = $locationModel->listofStages($this->id);
    $stagesIds = [];
    foreach($stages as $s) {
      $stagesIds[] = $s->id;
    }
    parent::__construct($database, $user, "chat_global", "area", $stage->area, "current_stage", $stagesIds);
  }
}

interface GlobalChatControlFactory {
  /** @return \HeroesofAbenez\Chat\GlobalChatControl */
  function create();
}
?>