<?php
namespace HeroesofAbenez\Chat;

/**
 * Global Chat Control
 *
 * @author Jakub Konečný
 */
class GlobalChatControl extends ChatControl {
  /** @var \HeroesofAbenez\Location */
  protected $locationModel;
  
  /**
   * @param \Nette\Database\Context $database
   * @param \Nette\Security\User $user
   * @param \HeroesofAbenez\Location $locationModel
   */
  function __construct(\Nette\Database\Context $database, \Nette\Security\User $user, \HeroesofAbenez\Location $locationModel) {
    $this->locationModel = $locationModel;
    $stage = $this->locationModel->getStage($user->identity->stage);
    $stages = $this->locationModel->listofStages($this->id);
    $stagesIds = array();
    foreach($stages as $s) {
      $stagesIds[] = $s->id;
    }
    parent::__construct($database, "chat_global", "area", $stage->area, "current_stage", $stagesIds);
  }
}
?>