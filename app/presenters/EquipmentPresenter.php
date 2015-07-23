<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Equipment
 *
 * @author Jakub Konečný
 */
class EquipmentPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Equipment @autowire */
  protected $model;
  /** @var \HeroesofAbenez\Model\Character @autowire */
  protected $characterModel;
  /** @var \HeroesofAbenez\Model\Profile @autowire */
  protected $profileModel;
  
  /**
   * @return void
   */
  function renderView($id) {
    $item = $this->model->view($id);
    if(!$item) $this->forward("notfound");
    $classes = $this->characterModel->getClassesList();
    foreach($item as $key => $value) {
      if($key == "required_class") $value = $classes[$value];
      $this->template->$key = $value;
    }
    $this->template->level = $this->user->identity->level;
    $this->template->class = $this->profileModel->getClassName($this->user->identity->occupation);
  }
}
?>