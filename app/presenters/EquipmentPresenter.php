<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Equipment
 *
 * @author Jakub Konečný
 */
class EquipmentPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Equipment */
  protected $model;
  
  /**
   * @param \HeroesofAbenez\Equipment $model
   */
  function __construct(\HeroesofAbenez\Equipment $model) {
     $this->model = $model;
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
    $profileModel = $this->context->getService("model.profile");
    $this->template->level = $this->user->identity->level;
    $this->template->class = $profileModel->getClassName($this->user->identity->occupation);
  }
}
?>