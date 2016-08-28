<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\StageNotFoundException,
    HeroesofAbenez\Model\CannotTravelToStageException;

  /**
   * Presenter Travel
   * 
   * @author Jakub Konečný
   */
class TravelPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Location @autowire */
  protected $model;
  
  /**
   * @return void
   */
  function renderDefault() {
    $this->redirect("Map:local");
  }
  
  /**
   * @param int $id Area to travel to
   * @return void
   */
  function actionArea($id) { }
  
  /**
   * @param int $id Stage to travel to
   * @return void
   */
  function actionStage($id) {
    $this->model->user = $this->user;
    try {
      $this->model->travelToStage($id);$stageName = $this->model->getStageName($id);
      $this->user->logout();
      $this->flashMessage($this->translator->translate("messages.travel.movedToSage", NULL, ["stageName" => $stageName]));
    } catch(CannotTravelToStageException $e) {
      $this->flashMessage($this->translator->translate("error.travel.cannotTravelToStage"));
    } catch(StageNotFoundException $e) {
      $this->flashMessage($this->translator->translate("error.travel.stageDoesNotExist"));
    }
    $this->redirect("Homepage:");
  }
}
?>