<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Equipment
 *
 * @author Jakub Konečný
 */
final class EquipmentPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Equipment */
  protected $model;
  /** @var \HeroesofAbenez\Model\Profile */
  protected $profileModel;
  
  public function __construct(\HeroesofAbenez\Model\Equipment $model, \HeroesofAbenez\Model\Profile $profileModel) {
    parent::__construct();
    $this->model = $model;
    $this->profileModel = $profileModel;
  }
  
  public function renderView(int $id): void {
    $item = $this->model->view($id);
    if(is_null($item)) {
      $this->forward("notfound");
    }
    $this->template->item = $item;
    $this->template->level = $this->user->identity->level;
    $this->template->class = $this->user->identity->occupation;
  }
}
?>