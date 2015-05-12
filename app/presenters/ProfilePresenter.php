<?php
namespace HeroesofAbenez\Presenters;

use \HeroesofAbenez as HOA;

  /**
   * Presenter Profile
   * 
   * @author Jakub Konečný
   */
class ProfilePresenter extends BasePresenter {
  function actionDefault() {
    $this->forward("view", $this->user->id);
  }
  
  /**
   * @param int $id id of character
   * @return void
   */
  function renderView($id) {
    $data = HOA\Profile::view($id, $this->db);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>