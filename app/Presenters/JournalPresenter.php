<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\Friends;
use HeroesofAbenez\Model\Item;
use HeroesofAbenez\Model\Journal;
use HeroesofAbenez\Model\NotEnoughExperiencesException;
use HeroesofAbenez\Model\ItemNotFoundException;
use HeroesofAbenez\Model\ItemNotEquipableException;
use HeroesofAbenez\Model\ItemAlreadyEquippedException;
use HeroesofAbenez\Model\ItemNotWornException;
use HeroesofAbenez\Model\NotFriendsException;
use HeroesofAbenez\Model\Pet;
use HeroesofAbenez\Model\PetNotFoundException;
use HeroesofAbenez\Model\PetNotOwnedException;
use HeroesofAbenez\Model\PetNotDeployedException;
use HeroesofAbenez\Model\PetAlreadyDeployedException;
use HeroesofAbenez\Model\PetNotDeployableException;
use HeroesofAbenez\Model\CannotChooseSpecializationException;
use HeroesofAbenez\Model\Profile;
use HeroesofAbenez\Model\SpecializationAlreadyChosenException;
use HeroesofAbenez\Model\SpecializationNotChosenException;
use HeroesofAbenez\Model\SpecializationNotAvailableException;

/**
 * Presenter Journal
 *
 * @author Jakub Konečný
 */
final class JournalPresenter extends BasePresenter {
  public function __construct(private readonly Journal $model, private readonly Profile $profileModel, private readonly Item $itemModel, private readonly Pet $petModel, private readonly Friends $friendsModel) {
    parent::__construct();
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

  public function renderQuestsFinished(int $page = 1): void {
    $this->setView("quests");
    $this->template->active = false;
    $quests = $this->model->finishedQuests();
    $paginator = new \Nette\Utils\Paginator();
    $paginator->page = $page;
    $paginator->itemsPerPage = 20;
    $paginator->itemCount = $quests->countStored(); // @phpstan-ignore assign.propertyType
    $this->template->quests = $quests->limitBy($paginator->getLength(), $paginator->getOffset());
    $this->template->paginator = $paginator;
  }
  
  public function renderPets(): void {
    $this->template->pets = $this->model->pets();
  }

  public function renderFriends(): void {
    $this->template->friends = $this->model->friends();
  }
  
  public function handleEquipItem(int $itemId): never {
    try {
      $this->itemModel->equipItem($itemId);
    } catch(ItemNotFoundException) {
      $this->redirect("Item:notfound");
    } catch(ItemNotEquipableException) {
      $this->flashMessage("errors.equipment.notEquipable");
    } catch(ItemAlreadyEquippedException) {
      $this->flashMessage("errors.equipment.alreadyWorn");
    }
    $this->redirect("Journal:inventory");
  }
  
  public function handleUnequipItem(int $itemId): never {
    try {
      $this->itemModel->unequipItem($itemId);
      $this->flashMessage("messages.equipment.unequiped");
    } catch(ItemNotFoundException) {
      $this->redirect("Item:notfound");
    } catch(ItemNotWornException) {
      $this->flashMessage("errors.equipment.notWorn");
    }
    $this->redirect("Journal:inventory");
  }
  
  public function handleLevelUp(int $specialization = null): never {
    $this->profileModel->user = $this->user;
    try {
      $this->profileModel->levelUp($specialization);
      $this->reloadIdentity();
    } catch(NotEnoughExperiencesException) {
      $this->flashMessage("errors.journal.cannotLevelUp");
    } catch(CannotChooseSpecializationException) {
      $this->flashMessage("errors.journal.cannotChooseSpecialization");
    } catch(SpecializationAlreadyChosenException) {
      $this->flashMessage("errors.journal.specializationAlreadyChosen");
    } catch(SpecializationNotChosenException) {
      $this->flashMessage("errors.journal.specializationNotChosen");
    } catch(SpecializationNotAvailableException) {
      $this->flashMessage("errors.journal.specializationNotAvailable");
    }
    $this->redirect("Journal:");
  }
  
  public function handleDeployPet(int $petId): never {
    try {
      $this->petModel->user = $this->user;
      $this->petModel->deployPet($petId);
    } catch(PetNotFoundException) {
      $this->flashMessage("errors.pet.notFound");
    } catch(PetNotOwnedException) {
      $this->flashMessage("errors.pet.notOwned");
    } catch(PetAlreadyDeployedException) {
      $this->flashMessage("errors.pet.alreadyDeployed");
    } catch(PetNotDeployableException) {
      $this->flashMessage("errors.pet.notDeployable");
    }
    $this->redirect("Journal:pets");
  }
  
  public function handleDiscardPet(int $petId): never {
    try {
      $this->petModel->user = $this->user;
      $this->petModel->discardPet($petId);
      $this->flashMessage("messages.pet.discarded");
    } catch(PetNotFoundException) {
      $this->flashMessage("errors.pet.notFound");
    } catch(PetNotOwnedException) {
      $this->flashMessage("errors.pet.notOwned");
    } catch(PetNotDeployedException) {
      $this->flashMessage("errors.pet.notDeployed");
    }
    $this->redirect("Journal:pets");
  }

  public function handleRemoveFriend(int $id): never {
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