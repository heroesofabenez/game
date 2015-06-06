<?php
namespace HeroesofAbenez\Presenters;

  /**
   * Presenter Travel
   * 
   * @author Jakub Konečný
   */
class TravelPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Location */
  protected $model;
  
  /**
   * @return void
   */
  function startup() {
    parent::startup();
    $this->model = $this->context->getService("model.location");
  }
  
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
    $this->model->user = $this->context->getService("security.user");
    $result = $this->model->travelToStage($id);
    switch($result) {
case 1:
  $stageName = $this->model->getStageName($id);
  $this->user->logout();
  $this->flashMessage("You moved to $stageName.");
  break;
case 2:
  $this->flashMessage("Specified stage doesn't exist.");
  break;
case 3:
  $this->flashMessage("You can't travel to specified stage.");
  break;
case 4:
  $this->flashMessage("An error occured.");
  break;
    }
    $this->redirect("Homepage:");
  }
}
?>