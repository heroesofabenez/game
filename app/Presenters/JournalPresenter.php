<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\NotEnoughExperiencesException;
use HeroesofAbenez\Model\ItemNotFoundException;
use HeroesofAbenez\Model\ItemNotOwnedException;
use HeroesofAbenez\Model\ItemNotEquipableException;
use HeroesofAbenez\Model\ItemAlreadyEquippedException;
use HeroesofAbenez\Model\ItemNotWornException;
use HeroesofAbenez\Model\NotFriendsException;
use HeroesofAbenez\Model\PetNotFoundException;
use HeroesofAbenez\Model\PetNotOwnedException;
use HeroesofAbenez\Model\PetNotDeployedException;
use HeroesofAbenez\Model\PetAlreadyDeployedException;
use HeroesofAbenez\Model\PetNotDeployableException;
use HeroesofAbenez\Model\CannotChooseSpecializationException;
use HeroesofAbenez\Model\SpecializationAlreadyChosenException;
use HeroesofAbenez\Model\SpecializationNotChosenException;
use HeroesofAbenez\Model\SpecializationNotAvailableException;

/**
 * Presenter Journal
 *
 * @author Jakub Konečný
 */
final class JournalPresenter extends BasePresenter {
  protected \HeroesofAbenez\Model\Journal $model;
  protected \HeroesofAbenez\Model\Profile $profileModel;
  protected \HeroesofAbenez\Model\Item $itemModel;
  protected \HeroesofAbenez\Model\Pet $petModel;
  protected \HeroesofAbenez\Model\Friends $friendsModel;

  public function __construct(\HeroesofAbenez\Model\Journal $model, \HeroesofAbenez\Model\Profile $profileModel, \HeroesofAbenez\Model\Item $itemModel, \HeroesofAbenez\Model\Pet $petModel, \HeroesofAbenez\Model\Friends $friendsModel) {
    parent::__construct();
    $this->model = $model;
    $this->profileModel = $profileModel;
    $this->itemModel = $itemModel;
    $this->petModel = $petModel;
    $this->friendsModel = $friendsModel;
  }
  
  public function renderDefault(): void {
    $stats = $this->model->basic();
    foreach($stats as $key => $value) {
      $this->template->$key = $value;
    }
    $this->template->nextLevelExp = $this->profileModel->getLevelsRequirements()[$stats["level"] + 1];
    $this->profileModel->user = $this->user;
    $this->template->availableSpecializations = $this->profileModel->getAvailableSpecializations();
  }
  
  public function renderInventory(): void {
    $inventory = $this->model->inventory();
    foreach($inventory as $key => $value) {
      $this->template->$key = $value;
    }
  }
  
  public function renderQuests(): void {
    $this->template->quests = $this->model->currentQuests();
  }

  public function renderQuestsFinished(): void {
    $this->setView("quests");
    $this->template->active = false;
    $this->template->quests = $this->model->finishedQuests();
  }
  
  public function renderPets(): void {
    $this->template->pets = $this->model->pets();
  }

  public function renderFriends(): void {
    $this->template->friends = $this->model->friends();
  }
  
  public function handleEquipItem(int $itemId): void {
    try {
      $this->itemModel->equipItem($itemId);
    } catch(ItemNotFoundException $e) {
      $this->redirect("Item:notfound");
    } catch(ItemNotOwnedException $e) {
      $this->redirect("Item:notfound");
    } catch(ItemNotEquipableException $e) {
      $this->flashMessage("errors.equipment.notEquipable");
    } catch(ItemAlreadyEquippedException $e) {
      $this->flashMessage("errors.equipment.alreadyWorn");
    }
    $this->redirect("Journal:inventory");
  }
  
  public function handleUnequipItem(int $itemId): void {
    try {
      $this->itemModel->unequipItem($itemId);
      $this->flashMessage("messages.equipment.unequiped");
    } catch(ItemNotFoundException $e) {
      $this->redirect("Item:notfound");
    } catch(ItemNotOwnedException $e) {
      $this->redirect("Item:notfound");
    } catch(ItemNotWornException $e) {
      $this->flashMessage("errors.equipment.notWorn");
    }
    $this->redirect("Journal:inventory");
  }
  
  public function handleLevelUp(int $specialization = null): void {
    $this->profileModel->user = $this->user;
    try {
      $this->profileModel->levelUp($specialization);
      $this->reloadIdentity();
    } catch(NotEnoughExperiencesException $e) {
      $this->flashMessage("errors.journal.cannotLevelUp");
    } catch(CannotChooseSpecializationException $e) {
      $this->flashMessage("errors.journal.cannotChooseSpecialization");
    } catch(SpecializationAlreadyChosenException $e) {
      $this->flashMessage("errors.journal.specializationAlreadyChosen");
    } catch(SpecializationNotChosenException $e) {
      $this->flashMessage("errors.journal.specializationNotChosen");
    } catch(SpecializationNotAvailableException $e) {
      $this->flashMessage("errors.journal.specializationNotAvailable");
    }
    $this->redirect("Journal:");
  }
  
  public function handleDeployPet(int $petId): void {
    try {
      $this->petModel->user = $this->user;
      $this->petModel->deployPet($petId);
    } catch(PetNotFoundException $e) {
      $this->flashMessage("errors.pet.notFound");
    } catch(PetNotOwnedException $e) {
      $this->flashMessage("errors.pet.notOwned");
    } catch(PetAlreadyDeployedException $e) {
      $this->flashMessage("errors.pet.alreadyDeployed");
    } catch(PetNotDeployableException $e) {
      $this->flashMessage("errors.pet.notDeployable");
    }
    $this->redirect("Journal:pets");
  }
  
  public function handleDiscardPet(int $petId): void {
    try {
      $this->petModel->user = $this->user;
      $this->petModel->discardPet($petId);
      $this->flashMessage("messages.pet.discarded");
    } catch(PetNotFoundException $e) {
      $this->flashMessage("errors.pet.notFound");
    } catch(PetNotOwnedException $e) {
      $this->flashMessage("errors.pet.notOwned");
    } catch(PetNotDeployedException $e) {
      $this->flashMessage("errors.pet.notDeployed");
    }
    $this->redirect("Journal:pets");
  }

  public function handleRemoveFriend(int $id): void {
    try {
      $this->friendsModel->remove($id);
      $this->flashMessage("messages.friends.removed");
    } catch(NotFriendsException $e) {
      $this->flashMessage("errors.friendship.notFriends");
    }
    $this->redirect("Journal:friends");
  }
}
?>