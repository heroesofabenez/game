<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Equipment
 *
 * @author Jakub Konečný
 */
class EquipmentPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\EquipmentModel */
  protected $model;
  
  /**
   * @return void
   */
  function startup() {
    parent::startup();
    $this->model = $this->context->getService("model.equipment");
  }
  
  /**
   * @return void
   */
  function renderView($id) {
    $item = $this->model->view($id);
    if(!$item) $this->forward("notfound");
    $characterModel = $this->context->getService("model.character");
    $classes = $characterModel->getClassesList();
    foreach($item as $key => $value) {
      if($key == "required_class") $value = $classes[$value];
      $this->template->$key = $value;
    }
  }
}
?>