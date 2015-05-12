<?php
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
    $this->part = $this->template->part = Intro::getIntroPosition($this->db, $this->user->id);
  }
  
  /**
   * @return void
   */
  function renderDefault() {
    $text = Intro::getIntroPart($this->db, $this->user->id, $this->part);
    if($text == "ENDOFINTRO") $this->forward("Intro:end");
    $this->template->intro = $text;
  }
  
  /**
   * @return void
   */
  function actionNext() {
    Intro::moveToNextPart($this->part + 1, $this->user->id, $this->db);
    $this->redirect("Intro:");
  }
  
  /**
   * @return void
   */
  function actionEnd() {
    Intro::endIntro($this->db, $this->user->identity);
    $this->redirect("Homepage:");
  }
}
