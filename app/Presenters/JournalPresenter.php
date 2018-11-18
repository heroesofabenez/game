<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\NotEnoughExperiencesException;
use HeroesofAbenez\Model\ItemNotFoundException;
use HeroesofAbenez\Model\ItemNotOwnedException;
use HeroesofAbenez\Model\ItemNotEquipableException;
use HeroesofAbenez\Model\ItemAlreadyEquippedException;
use HeroesofAbenez\Model\ItemNotWornException;
use HeroesofAbenez\Model\PetNotFoundException;
use HeroesofAbenez\Model\PetNotOwnedException;
use HeroesofAbenez\Model\PetNotDeployedException;
use HeroesofAbenez\Model\PetAlreadyDeployedException;

/**
 * Presenter Journal
 *
 * @author Jakub Konečný
 */
final class JournalPresenter extends BasePresenter {
  /** @var \HeroesofAbenez\Model\Journal */
  protected $model;
  /** @var \HeroesofAbenez\Model\Profile */
  protected $profileModel;
  /** @var \HeroesofAbenez\Model\Item */
  protected $itemModel;
  /** @var \HeroesofAbenez\Model\Pet */
  protected $petModel;

  public function __construct(\HeroesofAbenez\Model\Journal $model, \HeroesofAbenez\Model\Profile $profileModel, \HeroesofAbenez\Model\Item $itemModel, \HeroesofAbenez\Model\Pet $petModel) {
    parent::__construct();
    $this->model = $model;
    $this->profileModel = $profileModel;
    $this->itemModel = $itemModel;
    $this->petModel = $petModel;
  }
  
  public function renderDefault(): void {
    $stats = $this->model->basic();
    foreach($stats as $key => $value) {
      $this->template->$key = $value;
    }
    $this->template->nextLevelExp = $this->profileModel->getLevelsRequirements()[$stats["level"]  + 1];
  }
  
  public function renderInventory(): void {
    $inventory = $this->model->inventory();
    foreach($inventory as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
  public function renderQuests(): void {
    $this->template->quests = $this->model->quests();
  }
  
  public function renderPets(): void {
    $this->template->pets = $this->model->pets();
  }
  
  public function handleEquipItem(int $itemId): void {
    try {
      $this->itemModel->equipItem($itemId);
    } catch(ItemNotFoundException $e) {
      $this->redirect("Equipment:notfound");
    } catch(ItemNotOwnedException $e) {
      $this->redirect("Equipment:notfound");
    } catch(ItemNotEquipableException $e) {
      $this->flashMessage($this->translator->translate("errors.equipment.notEquipable"));
    } catch(ItemAlreadyEquippedException $e) {
      $this->flashMessage($this->translator->translate("errors.equipment.alreadyWorn"));
    }
    $this->redirect("Journal:inventory");
  }
  
  public function handleUnequipItem(int $itemId): void {
    try {
      $this->itemModel->unequipItem($itemId);
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
  
  public function handleLevelUp(): void {
    $this->profileModel->user = $this->user;
    try {
      $this->profileModel->levelUp();
      $this->user->logout();
    } catch(NotEnoughExperiencesException $e) {
      $this->flashMessage($this->translator->translate("errors.journal.cannotLevelUp"));
    }
    $this->redirect("Journal:");
  }
  
  public function handleDeployPet(int $petId): void {
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
  
  public function handleDiscardPet(int $petId): void {
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