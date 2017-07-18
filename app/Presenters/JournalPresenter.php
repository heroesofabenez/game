<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\NotEnoughExperiencesException,
    HeroesofAbenez\Model\ItemNotFoundException,
    HeroesofAbenez\Model\ItemNotOwnedException,
    HeroesofAbenez\Model\ItemAlreadyEquippedException,
    HeroesofAbenez\Model\ItemNotWornException,
    HeroesofAbenez\Model\PetNotFoundException,
    HeroesofAbenez\Model\PetNotOwnedException,
    HeroesofAbenez\Model\PetNotDeployedException,
    HeroesofAbenez\Model\PetAlreadyDeployedException;

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
  /** @var \HeroesofAbenez\Model\Pet @autowire */
  protected $petModel;
  
  function renderDefault(): void {
    $stats = $this->model->basic();
    foreach($stats as $key => $value) {
      $this->template->$key = $value;
    }
    $this->template->nextLevelExp = $this->profileModel->getLevelsRequirements()[$stats["level"]  + 1];
  }
  
  function renderInventory(): void {
    $inventory = $this->model->inventory();
    foreach($inventory as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
  function renderQuests(): void {
    $this->template->quests = $this->model->quests();
  }
  
  function renderPets(): void {
    $this->template->pets = $this->model->pets();
  }
  
  function handleEquipItem(int $itemId): void {
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
  
  function handleUnequipItem(int $itemId): void {
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
  
  function handleLevelUp(): void {
    $this->profileModel->user = $this->user;
    try {
      $this->profileModel->levelUp();
      $this->user->logout();
    } catch(NotEnoughExperiencesException $e) {
      $this->flashMessage($this->translator->translate("errors.journal.cannotLevelUp"));
    }
    $this->redirect("Journal:");
  }
  
  function handleDeployPet(int $petId): void {
    try {
      $this->petModel->user = $this->user;
      $this->petModel->deployPet($petId);
    } catch(PetNotFoundException $e) {
      $this->flashMessage($this->translator->translate("errors.pet.notFound"));
    } catch(PetNotOwnedException $e) {
      $this->flashMessage($this->translator->translate("errors.pet.notOwned"));
    } catch(PetAlreadyDeployedException $e) {
      $this->flashMessage($this->translator->translate("errors.pet.alreadyDeployed"));
    }
    $this->redirect("Journal:pets");
  }
  
  function handleDiscardPet(int $petId): void {
    try {
      $this->petModel->user = $this->user;
      $this->petModel->discardPet($petId);
      $this->flashMessage($this->translator->translate("messages.pet.discarded"));
    } catch(PetNotFoundException $e) {
      $this->flashMessage($this->translator->translate("errors.pet.notFound"));
    } catch(PetNotOwnedException $e) {
      $this->flashMessage($this->translator->translate("errors.pet.notOwned"));
    } catch(PetNotDeployedException $e) {
      $this->flashMessage($this->translator->translate("errors.pet.notDeployed"));
    }
    $this->redirect("Journal:pets");
  }
}
?>