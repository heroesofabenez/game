<?php
declare(strict_types=1);

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
  
  public function __construct(Model\Location $locationModel, \Nette\Security\User $user, Model\NPC $npcModel) {
    $this->model = $locationModel;
    $this->model->user = $user;
    $this->model->npcModel = $npcModel;
    parent::__construct();
  }
  
  public function renderDefault(): void {
    $this->template->stage = $this->model->getStage($this->user->identity->stage);
  }
}
?>