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
  function actionStage($id) { }
}
?>