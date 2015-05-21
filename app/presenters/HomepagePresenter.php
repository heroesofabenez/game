<?php
namespace HeroesofAbenez\Presenters;

use \HeroesofAbenez as HOA;

  /**
   * Presenter Homepage
   * 
   * @author Jakub Konečný
   */
class HomepagePresenter extends BasePresenter {
  function renderDefault() {
    $stages = HOA\Location::listOfStages($this->context);
    $stage = $stages[$this->user->identity->stage];
    $this->template->stageName = $stage->name;
    $this->template->areaName = HOA\Location::getAreaName($stage->area, $this->context);
  }
}
?>