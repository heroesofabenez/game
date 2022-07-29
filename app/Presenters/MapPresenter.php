<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\ApplicationDirectories;
use HeroesofAbenez\Model\Location;
use HeroesofAbenez\Model\Map;
use HeroesofAbenez\Orm\QuestStage;

/**
 * Presenter Map
 *
 * @author Jakub Konečný
 */
final class MapPresenter extends BasePresenter {
  private Map $model;
  private Location $locationModel;
  private ApplicationDirectories $directories;
  
  public function __construct(Map $model, Location $locationModel, ApplicationDirectories $directories) {
    parent::__construct();
    $this->model = $model;
    $this->locationModel = $locationModel;
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

  public function actionGlobal(): void {
    $data = $this->model->global();
    $this->template->wwwDir = $this->directories->wwwDir;
    /** @var QuestStage $currentStage */
    $currentStage = $this->locationModel->getStage($this->user->identity->stage);
    foreach($data as $key => $value) {
      if($key === "areas") {
        foreach($value as $area) {
          $area->href = "";
          if($area->area !== $currentStage->area->id) {
            $area->href = $this->link("Travel:area", $area->area);
          }
        }
      }
      $this->template->$key = $value;
    }
  }
}
?>