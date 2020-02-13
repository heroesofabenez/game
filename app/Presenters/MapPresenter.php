<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Map
 *
 * @author Jakub Konečný
 */
final class MapPresenter extends BasePresenter {
  protected \HeroesofAbenez\Model\Map $model;
  
  public function __construct(\HeroesofAbenez\Model\Map $model) {
    parent::__construct();
    $this->model = $model;
  }
  
  public function actionLocal(): void {
    $data = $this->model->local();
    $this->template->wwwDir = realpath(__DIR__ . "/../../");
    foreach($data as $key => $value) {
      if($key === "areas") {
        foreach($value as $area) {
          $area->href = "";
          if($area->stage !== $this->user->identity->stage) {
            $area->href = $this->link("Travel:stage", $area->stage);
          }
        }
      }
      $this->template->$key = $value;
    }
  }
}
?>