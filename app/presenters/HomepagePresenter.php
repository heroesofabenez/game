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
    $stages = $locationModel->listOfStages();
    $stage = $stages[$this->user->identity->stage];
    $this->template->stageName = $stage->name;
    $this->template->areaName = $locationModel->getAreaName($stage->area);
    $this->template->characterName = $this->user->identity->name;
    $npcMOdel = $this->context->getService("model.npc");
    $this->template->npcs = $npcMOdel->listOfNpcs($stage->id);
  }
}
?>