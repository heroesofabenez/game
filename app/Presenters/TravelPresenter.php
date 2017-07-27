<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\StageNotFoundException,
    HeroesofAbenez\Model\CannotTravelToStageException;

  /**
   * Presenter Travel
   * 
   * @author Jakub Konečný
   */
class TravelPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Location @autowire */
  protected $model;
  
  public function renderDefault(): void {
    $this->redirect("Map:local");
  }
  
  public function actionArea(int $id): void {
    
  }
  
  public function actionStage(int $id): void {
    $this->model->user = $this->user;
    try {
      $this->model->travelToStage($id);
      $stageName = $this->model->getStageName($id);
      $this->user->logout();
      $this->flashMessage($this->translator->translate("messages.travel.movedToStage", 0, ["stageName" => $stageName]));
    } catch(CannotTravelToStageException $e) {
      $this->flashMessage($this->translator->translate("error.travel.cannotTravelToStage"));
    } catch(StageNotFoundException $e) {
      $this->flashMessage($this->translator->translate("error.travel.stageDoesNotExist"));
    }
    $this->redirect("Homepage:");
  }
}
?>