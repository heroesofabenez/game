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
    if($intros->count("*") == 0) {
      $this->template->intro = "";
      return;
    }
    foreach($intros as $intro) { }
    $this->template->intro = $intro->text;
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
}
