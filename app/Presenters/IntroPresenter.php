<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Intro
 *
 * @author Jakub Konečný
 */
final class IntroPresenter extends BasePresenter {
  /** @var int In which part of intro we are */
  protected $part;
  /** @var \HeroesofAbenez\Model\Intro */
  protected $model;
  
  public function __construct(\HeroesofAbenez\Model\Intro $model) {
    parent::__construct();
    $this->model = $model;
  }
  
  protected function startup(): void {
    $this->reloadIdentity();
    parent::startup();
    $this->part = $this->template->part = $this->model->getIntroPosition();
  }
  
  public function renderDefault(): void {
    $text = $this->model->getIntroPart($this->part);
    if($text === "") {
      $this->forward("Intro:end");
    }
    $this->template->intro = $text;
  }
  
  public function actionNext(): void {
    $this->model->moveToNextPart();
    $this->redirect("Intro:");
  }
  
  public function actionEnd(): void {
    $this->model->endIntro();
    $this->reloadIdentity();
    $this->redirect("Homepage:");
  }
}
?>