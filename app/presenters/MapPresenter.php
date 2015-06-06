<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Map
 *
 * @author Jakub Konečný
 */
class MapPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\MapDrawer */
  protected $model;
  
  /**
   * @return void
   */
  function startup() {
    parent::startup();
    $this->model = $this->context->getService("model.mapdrawer");
  }
  
  /**
   * @todo show map
   * @return void
   */
  function actionLocal() {
    $data= $this->model->localMap();
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>