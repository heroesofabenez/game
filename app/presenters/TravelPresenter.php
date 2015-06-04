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
   * @todo show map
   * @return void
   */
  function renderDefault() {
    $stages = $this->model->listOfStages();
    $curr_stage = $stages[$this->user->identity->stage];
    $this->template->currentStage = $curr_stage->id;
    $this->template->currentArea = $curr_stage->area;
    foreach($stages as $stage) {
      if($stage->area !== $curr_stage->area) unset($stages[$stage->id]);
    }
    $this->template->stages = $stages;
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