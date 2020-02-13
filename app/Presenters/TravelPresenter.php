<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\AreaNotFoundException;
use HeroesofAbenez\Model\CannotTravelToAreaException;
use HeroesofAbenez\Model\StageNotFoundException;
use HeroesofAbenez\Model\CannotTravelToStageException;

  /**
   * Presenter Travel
   * 
   * @author Jakub Konečný
   */
final class TravelPresenter extends BasePresenter {
  protected \HeroesofAbenez\Model\Location $model;
  
  public function __construct(\HeroesofAbenez\Model\Location $model) {
    parent::__construct();
    $this->model = $model;
  }
  
  public function renderDefault(): void {
    $this->redirect("Map:local");
  }
  
  public function actionArea(int $id): void {
    $this->model->user = $this->user;
    try {
      $this->model->travelToArea($id);
      $areaName = $this->translator->translate("areas.$id.name");
      $this->reloadIdentity();
      $this->flashMessage($this->translator->translate("messages.travel.movedToArea", 0, ["areaName" => $areaName]));
    } catch(CannotTravelToAreaException $e) {
      $this->flashMessage("errors.travel.cannotTravelToArea");
    } catch(AreaNotFoundException $e) {
      $this->flashMessage("errors.travel.areaDoesNotExist");
    }
    $this->redirect("Homepage:");
  }
  
  public function actionStage(int $id): void {
    $this->model->user = $this->user;
    try {
      $this->model->travelToStage($id);
      $stageName = $this->translator->translate("stages.$id.name");
      $this->reloadIdentity();
      $this->flashMessage($this->translator->translate("messages.travel.movedToStage", 0, ["stageName" => $stageName]));
    } catch(CannotTravelToStageException $e) {
      $this->flashMessage("errors.travel.cannotTravelToStage");
    } catch(StageNotFoundException $e) {
      $this->flashMessage("errors.travel.stageDoesNotExist");
    }
    $this->redirect("Homepage:");
  }
}
?>