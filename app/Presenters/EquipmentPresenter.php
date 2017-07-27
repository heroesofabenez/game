<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Equipment
 *
 * @author Jakub Konečný
 */
class EquipmentPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Equipment @autowire */
  protected $model;
  /** @var \HeroesofAbenez\Model\Profile @autowire */
  protected $profileModel;
  
  public function renderView(int $id): void {
    $item = $this->model->view($id);
    if(!$item) {
      $this->forward("notfound");
    }
    $this->template->item = $item;
    $this->template->level = $this->user->identity->level;
    $this->template->class = $this->user->identity->occupation;
  }
}
?>