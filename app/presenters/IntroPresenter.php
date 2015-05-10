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
  function actionDefault() {
    $char = $this->db->table("characters")->get($this->user->id);
    $this->part = $this->template->part = $char->intro;
  }
  
  /**
   * @return void
   */
  function renderDefault() {  }
}
