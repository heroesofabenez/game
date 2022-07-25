<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model;

  /**
   * Presenter Homepage
   * 
   * @author Jakub Konečný
   */
final class HomepagePresenter extends BasePresenter {
  private Model\Location $model;
  
  public function __construct(Model\Location $locationModel, \Nette\Security\User $user) {
    $this->model = $locationModel;
    $this->model->user = $user;
    parent::__construct();
  }
  
  public function renderDefault(): void {
    $stage = $this->model->getStage($this->user->identity->stage);
    $this->template->stage = $stage;
  }
}
?>