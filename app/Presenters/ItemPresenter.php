<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Item
 *
 * @author Jakub Konečný
 */
class ItemPresenter extends BasePresenter {
  /**  @var \HeroesofAbenez\Model\Item @autowire */
  protected $model;
  
  /**
   * @param int $id
   * @return void
   */
  function renderView($id) {
    $item = $this->model->view($id);
    if(!$item) $this->forward("notfound");
    $this->template->item = $item->id;
  }
}
?>