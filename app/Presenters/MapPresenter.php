<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Map
 *
 * @author Jakub Konečný
 */
class MapPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Map @autowire */
  protected $model;
  
  /**
   * @return void
   */
  function actionLocal() {
    $data = $this->model->local();
    $this->template->wwwDir = WWW_DIR;
    foreach($data as $key => $value) {
      if($key == "areas") {
        foreach($value as $area) {
          if($area->stage == $this->user->identity->stage) {
            $area->href = "";
          } else {
            $area->href = $this->link("Travel:stage", $area->stage);
          }
        }
      }
      $this->template->$key = $value;
    }
  }
}
?>