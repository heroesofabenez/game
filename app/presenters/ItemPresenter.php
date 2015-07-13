<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Item
 *
 * @author Jakub Konečný
 */
class ItemPresenter extends BasePresenter {
  /**  @var \HeroesofAbenez\Model\Item */
  protected $model;
  
  /**
   * @param \HeroesofAbenez\Model\Item $model
   */
  function __construct(\HeroesofAbenez\Model\Item $model) {
    $this->model = $model;
  }
  
  /**
   * @param int $id
   * @return void
   */
  function renderView($id) {
    $item = $this->model->view($id);
    if(!$item) $this->forward("notfound");
    $this->template->item = $item;
  }
}
?>