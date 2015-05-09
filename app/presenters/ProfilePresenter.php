<?php
  /**
   * Presenter Profile
   * 
   * @author Jakub Konečný
   */
class ProfilePresenter extends BasePresenter {
  function actionDefault() {
    $this->forward("view", $this->user->id);
  }
  
  function renderView($id) {
    $data = Profile::view($id, $this->db);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>