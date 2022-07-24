<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\ApplicationDirectories;

/**
 * Presenter Map
 *
 * @author Jakub Konečný
 */
final class MapPresenter extends BasePresenter {
  protected \HeroesofAbenez\Model\Map $model;
  private ApplicationDirectories $directories;
  
  public function __construct(\HeroesofAbenez\Model\Map $model, ApplicationDirectories $directories) {
    parent::__construct();
    $this->model = $model;
    $this->directories = $directories;
  }
  
  public function actionLocal(): void {
    $data = $this->model->local();
    $this->template->wwwDir = $this->directories->wwwDir;
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