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
  $next = $this->part + 1;
  $data = array("intro" => $next);
  $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $this->user->id);
  $this->redirect("Intro:");
  }
  
  /**
   * @return void
   */
  function actionEnd() {
    $startingLocation = Intro::getStartingLocation($this->db, $this->user->identity);
    $data = array("current_stage" => $startingLocation);
    $this->db->query("UPDATE characters SET ? WHERE id=?", $data, $this->user->id);
    $this->redirect("Homepage:");
  }
}
