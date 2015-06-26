<?php
namespace HeroesofAbenez\Presenters;

  /**
   * Presenter Homepage
   * 
   * @author Jakub Konečný
   */
class HomepagePresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Location */
  protected $model;
  
  /**
   * @param \HeroesofAbenez\Location $locationModel
   * @param \Nette\Security\User $user
   * @param \HeroesofAbenez\NPCModel $npcModel
   */
  function __construct(\HeroesofAbenez\Location $locationModel, \Nette\Security\User $user, \HeroesofAbenez\NPCModel $npcModel) {
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