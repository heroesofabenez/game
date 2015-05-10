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
  function renderDefault() {
    $char = $this->db->table("characters")->get($this->user->id);
    $intros = $this->db->table("introduction")
      ->where("race", $char->race)
      ->where("class", $char->occupation)
      ->where("part", $this->part);
    if($intros->count("text") == 0) {
      $this->template->intro = "";
      return;
    }
    foreach($intros as $intro) { }
    $this->template->intro = $intro->text;
  }
}
