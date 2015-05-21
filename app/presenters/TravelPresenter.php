<?php
namespace HeroesofAbenez\Presenters;

  /**
   * Presenter Travel
   * 
   * @author Jakub Konečný
   */
class TravelPresenter extends BasePresenter {
  /**
   * @todo show map
   * @return void
   */
  function renderDefault() { }
  
  /**
   * @param int $location Area to travel to
   * @return void
   */
  function actionArea($location) { }
  
  /**
   * @param int $location Stage to travel to
   * @return void
   */
  function actionStage($location) { }
}
?>