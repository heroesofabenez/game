<?php
namespace HeroesofAbenez\Presenters;

  /**
   * Presenter Homepage
   * 
   * @author Jakub Konečný
   */
class HomepagePresenter extends BasePresenter {
  /**
   * @return void
   */
  function renderDefault() {
    $locationModel = $this->context->getService("model.location");
    $locationModel->user = $this->context->getService("security.user");
    $locationModel->npcModel = $this->context->getService("model.npc");
    $data = $locationModel->home($this->user->identity->stage);
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>