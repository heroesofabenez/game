<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model;

  /**
   * Presenter Homepage
   * 
   * @author Jakub Konečný
   */
class HomepagePresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Location */
  protected $model;
  
  /**
   * @param \HeroesofAbenez\Model\Location $locationModel
   * @param \Nette\Security\User $user
   * @param \HeroesofAbenez\Model\NPC $npcModel
   */
  function __construct(Model\Location $locationModel, \Nette\Security\User $user, Model\NPC $npcModel) {
    $this->model = $locationModel;
    $this->model->user = $user;
    $this->model->npcModel = $npcModel;
  }
  
  /**
   * @return void
   */
  function renderDefault() {
    $data = $this->model->home();
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>