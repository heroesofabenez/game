<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Journal
 *
 * @author Jakub Konečný
 */
class JournalPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Journal @autowire */
  protected $model;
  
  /**
   * @return void
   */
  function renderDefault() {
    $this->model->locationModel = $this->context->getService("model.location");
    $this->model->guildModel = $this->context->getService("model.guild");
    $stats = $this->model->basic();
    foreach($stats as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
  /**
   * @return void
   */
  function renderInventory() {
    $this->model->equipmentModel = $this->context->getService("model.equipment");
    $inventory = $this->model->inventory();
    foreach($inventory as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
  /**
   * @return void
   */
  function renderQuests() {
    $this->model->questModel = $this->context->getService("model.quest");
    $this->template->quests = $this->model->quests();
  }
  
  /**
   * @return void
   */
  function renderPets() {
    $this->template->pets = $this->model->pets();
  }
}
?>