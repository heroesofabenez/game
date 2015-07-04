<?php
namespace HeroesofAbenez\Presenters;

/**
 * Presenter Journal
 *
 * @author Jakub Konečný
 */
class JournalPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Journal */
  protected $model;
  
  /**
   * @param \HeroesofAbenez\Model\Journal $model
   */
  function __construct(\HeroesofAbenez\Model\Journal $model) {
    $this->model = $model;
  }
  
  /**
   * @return void
   */
  function renderDefault() {
    $this->model->locationModel = $this->context->getService("model.location");
    $this->model->guildModel = $this->context->getService("model.guild");
    $this->model->profileModel = $this->context->getService("model.profile");
    $stats = $this->model->basic();
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