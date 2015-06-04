<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Intro
 *
 * @author Jakub Konečný
 */
class IntroPresenter extends BasePresenter {
  /** @var int In which part of intro we are */
  protected $part;
  /** @var \HeroesofAbenez\Intro */
  protected $model;
  
  /**
   * @return void
   */
  function startup() {
    $this->user->logout();
    parent::startup();
    $this->model = $this->context->getService("model.intro");
    $this->part = $this->template->part = $this->model->getIntroPosition();
  }
  
  /**
   * @return void
   */
  function renderDefault() {
    $text = $this->model->getIntroPart($this->part);
    if($text == "ENDOFINTRO") $this->forward("Intro:end");
    $this->template->intro = $text;
  }
  
  /**
   * @return void
   */
  function actionNext() {
    $this->model->moveToNextPart($this->part + 1);
    $this->redirect("Intro:");
  }
  
  /**
   * @return void
   */
  function actionEnd() {
    $this->model->endIntro();
    $this->user->logout();
    $this->redirect("Homepage:");
  }
}
?>