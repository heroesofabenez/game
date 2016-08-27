<?php
namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\NotEnoughExperiencesException,
    HeroesofAbenez\Model\ItemNotFoundException,
    HeroesofAbenez\Model\ItemNotOwnedException,
    HeroesofAbenez\Model\ItemAlreadyEquippedException,
    HeroesofAbenez\Model\ItemNotWornException;

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
  /** @var \HeroesofAbenez\Model\Equipment @autowire */
  protected $equipmentModel;
  
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
   * @param int $itemId
   * @return void
   */
  function handleEquipItem($itemId) {
    try {
      $this->equipmentModel->equipItem($itemId);
    } catch(ItemNotFoundException $e) {
      $this->redirect("Equipment:notfound");
    } catch(ItemNotOwnedException $e) {
      $this->redirect("Equipment:notfound");
    } catch(ItemAlreadyEquippedException $e) {
      $this->flashMessage($this->translator->translate("errors.equipment.alreadyWorn"));
    }
    $this->redirect("Journal:inventory");
  }
  
  /**
   * @param int $itemId
   * @return void
   */
  function handleUnequipItem($itemId) {
    try {
      $this->equipmentModel->unequipItem($itemId);
      $this->flashMessage($this->translator->translate("errors.equipment.unequiped"));
    } catch(ItemNotFoundException $e) {
      $this->redirect("Equipment:notfound");
    } catch(ItemNotOwnedException $e) {
      $this->redirect("Equipment:notfound");
    } catch(ItemNotWornException $e) {
      $this->flashMessage($this->translator->translate("errors.equipment.notWorn"));
    }
    $this->redirect("Journal:inventory");
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