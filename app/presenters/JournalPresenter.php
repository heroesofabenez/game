<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Journal
 *
 * @author Jakub Konečný
 */
class JournalPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Journal */
  protected $model;
  
  /**
   * @return void
   */
  function startup() {
    parent::startup();
    $this->model = $this->context->getService("model.journal");
  }
  
  /**
   * @return void
   */
  function renderDefault() {
    $stats = $this->model->basic($this->context);
    foreach($stats as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
  /**
   * @return void
   */
  function renderInventory() {
    $inventory = $this->model->inventory();
    foreach($inventory as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
  /**
   * @return void
   */
  function renderQuests() {
    $quests = $this->model->quests();
    $this->template->quests = $quests;
  }
  
  /**
   * @return void
   */
  function renderPets() {
    $pets = $this->model->pets();
    $this->template->pets = $pets;
  }
}
?>