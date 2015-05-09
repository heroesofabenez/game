<?php
class ProfilePresenter extends BasePresenter {
  /**
   * Presenter Profile
   * 
   * @author Jakub Konečný
   */
  
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