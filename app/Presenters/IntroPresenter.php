<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Intro
 *
 * @author Jakub Konečný
 */
class IntroPresenter extends BasePresenter {
  /** @var int In which part of intro we are */
  protected $part;
  /** @var \HeroesofAbenez\Model\Intro @autowire */
  protected $model;
  
  public function startup(): void {
    $this->user->logout();
    parent::startup();
    $this->part = $this->template->part = $this->model->getIntroPosition();
  }
  
  public function renderDefault(): void {
    $text = $this->model->getIntroPart($this->part);
    if($text == "ENDOFINTRO") {
      $this->forward("Intro:end");
    }
    $this->template->intro = $text;
  }
  
  public function actionNext(): void {
    $this->model->moveToNextPart($this->part + 1);
    $this->redirect("Intro:");
  }
  
  public function actionEnd(): void {
    $this->model->endIntro();
    $this->user->logout();
    $this->redirect("Homepage:");
  }
}
?>