<?php
namespace HeroesofAbenez\Presenters;

use \HeroesofAbenez as HOA;

/**
 * Presenter Intro
 *
 * @author Jakub Konečný
 */
class IntroPresenter extends BasePresenter {
  /** @var int In which part of intro we are */
  protected $part;
  
  /**
   * @return void
   */
  function startup() {
    $this->user->logout();
    parent::startup();
    $this->part = $this->template->part = HOA\Intro::getIntroPosition($this->db, $this->user->id);
  }
  
  /**
   * @return void
   */
  function renderDefault() {
    $text = HOA\Intro::getIntroPart($this->db, $this->user->id, $this->part);
    if($text == "ENDOFINTRO") $this->forward("Intro:end");
    $this->template->intro = $text;
  }
  
  /**
   * @return void
   */
  function actionNext() {
    HOA\Intro::moveToNextPart($this->part + 1, $this->user->id, $this->db);
    $this->redirect("Intro:");
  }
  
  /**
   * @return void
   */
  function actionEnd() {
    HOA\Intro::endIntro($this->db, $this->user->identity);
    $this->user->logout();
    $this->redirect("Homepage:");
  }
}
