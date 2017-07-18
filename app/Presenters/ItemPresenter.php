<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

/**
 * Presenter Item
 *
 * @author Jakub Konečný
 */
class ItemPresenter extends BasePresenter {
  /**  @var \HeroesofAbenez\Model\Item @autowire */
  protected $model;
  
  function renderView(int $id): void {
    $item = $this->model->view($id);
    if(!$item) {
      $this->forward("notfound");
    }
    $this->template->item = $item->id;
  }
}
?>