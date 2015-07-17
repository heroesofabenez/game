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
  /** @var \HeroesofAbenez\Model\Intro */
  protected $model;
  
  function __construct(\HeroesofAbenez\Model\Intro $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function startup() {
    $this->user->logout();
    parent::startup();
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