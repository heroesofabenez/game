<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\NotEnoughExperiencesException;

/**
 * Presenter Journal
 *
 * @author Jakub Konečný
 */
class JournalPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Journal @autowire */
  protected $model;
  /** @var \HeroesofAbenez\Model\Profile @autowire */
  protected $profileModel;
  
  /**
   * @return void
   */
  function renderDefault() {
    $stats = $this->model->basic();
    foreach($stats as $key => $value) {
      $this->template->$key = $value;
    }
    $this->template->nextLevelExp = $this->profileModel->getLevelsRequirements()[$stats["level"]  + 1];
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
    $this->template->quests = $this->model->quests();
  }
  
  /**
   * @return void
   */
  function renderPets() {
    $this->template->pets = $this->model->pets();
  }
  
  /**
   * @return void
   */
  function handleLevelUp() {
    $this->profileModel->user = $this->user;
    try {
      $this->profileModel->levelUp();
      $this->user->logout();
    } catch(NotEnoughExperiencesException $e) {
      $this->flashMessage($this->translator->translate("errors.journal.cannotLevelUp"));
    }
    $this->redirect("Journal:");
  }
}
?>