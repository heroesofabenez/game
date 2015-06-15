<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Item
 *
 * @author Jakub Konečný
 */
class ItemPresenter extends BasePresenter {
  /**  @var \HeroesofAbenez\ItemModel */
  protected $model;
  
  /**
   * @return void
   */
  function startup() {
    parent::startup();
    $this->model = $this->context->getService("model.item");
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
  
  /**
   * @param int $id
   * @return void
   */
  function actionBuy($id) {
    $this->model->request = $this->context->getService("http.request");
    $this->model->linkGenerator = $this->context->getService("application.linkGenerator");
    $result = $this->model->buyItem($id);
    switch($result) {
case 1:
  $this->flashMessage("Item bought.");
  break;
case 2:
  $this->flashMessage("Specified item doesn't exist.");
  break;
case 3:
  $this->flashMessage("You can't buy the item from this location.");
  break;
case 4:
  $this->flashMessage("You don't have enough money.");
  break;
case 5:
  $this->flashMessage("An error occured.");
  break;
    }
    $referer = $this->context->getService("http.request")->getReferer();
    if(!$referer) $this->redirect("Homepage:");
    else $this->redirectUrl($referer->path);
  }
}
?>