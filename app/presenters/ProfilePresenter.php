<?php
namespace HeroesofAbenez\Presenters;

use \HeroesofAbenez as HOA;

  /**
   * Presenter Profile
   * 
   * @author Jakub Konečný
   */
class ProfilePresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Profile */
  protected $model;
  
  function __construct(\HeroesofAbenez\Profile $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function actionDefault() {
    $this->forward("view", $this->user->id);
  }
  
  /**
   * @param int $id id of character
   * @return void
   */
  function renderView($id) {
    $data = $this->model->view($id, $this->context);
    if(!$data) $this->forward("notfound");
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>