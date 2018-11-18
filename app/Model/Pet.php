<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\PetType;
use HeroesofAbenez\Orm\Pet as PetEntity;
use HeroesofAbenez\Orm\Model as ORM;

/**
 * Pet Model
 *
 * @author Jakub Konečný
 * @property-write \Nette\Security\User $user
 */
final class Pet {
  use \Nette\SmartObject;
  
  /** @var ORM */
  protected $orm;
  /** @var \Nette\Security\User */
  protected $user;
  
  public function __construct(ORM $orm) {
    $this->orm = $orm;
  }
  
  public function setUser(\Nette\Security\User $user): void {
    $this->user = $user;
  }
  
  /**
   * Get info about specified pet type
   */
  public function viewType(int $id): ?PetType {
    return $this->orm->petTypes->getById($id);
  }
  
  /**
   * Get specified user's active pet
   */
  public function getActivePet(int $user): ?PetEntity {
    return $this->orm->pets->getActivePet($user);
  }
  
  public function canDeployPet(PetEntity $pet): bool {
    if($this->user->identity->level < $pet->type->requiredLevel) {
      return false;
    } elseif(!is_null($pet->type->requiredClass) AND $pet->type->requiredClass->id !== $this->user->identity->occupation) {
      return false;
    } elseif(!is_null($pet->type->requiredRace) AND $pet->type->requiredRace->id !== $this->user->identity->race) {
      return false;
    }
    return true;
  }
  
  /**
   * Deploy a pet
   *
   * @throws PetNotFoundException
   * @throws PetNotOwnedException
   * @throws PetAlreadyDeployedException
   * @throws PetNotDeployableException
   */
  public function deployPet(int $id): void {
    $pet = $this->orm->pets->getById($id);
    if(is_null($pet)) {
      throw new PetNotFoundException();
    } elseif($pet->owner->id !== $this->user->id) {
      throw new PetNotOwnedException();
    } elseif($pet->deployed) {
      throw new PetAlreadyDeployedException();
    } elseif(!$this->canDeployPet($pet)) {
      throw new PetNotDeployableException();
    }
    $pets = $this->orm->pets->findByOwner($this->user->id);
    /** @var \HeroesofAbenez\Orm\Pet $pet */
    foreach($pets as $pet) {
      $pet->deployed = ($pet->id === $id);
      $this->orm->pets->persist($pet);
    }
    $this->orm->pets->flush();
  }
  
  /**
   * Discard a pet
   *
   * @throws PetNotFoundException
   * @throws PetNotOwnedException
   * @throws PetNotDeployedException
   */
  public function discardPet(int $id): void {
    $pet = $this->orm->pets->getById($id);
    if(is_null($pet)) {
      throw new PetNotFoundException();
    } elseif($pet->owner->id !== $this->user->id) {
      throw new PetNotOwnedException();
    } elseif(!$pet->deployed) {
      throw new PetNotDeployedException();
    }
    $pet->deployed = false;
    $this->orm->pets->persistAndFlush($pet);
  }

  /**
   * Give the player a pet of specified type (if they do not own one already)
   */
  public function givePet(int $type): void {
    $petType = $this->viewType($type);
    if(is_null($petType)) {
      return;
    }
    if(!is_null($this->orm->pets->getByTypeAndOwner($petType, $this->user->id))) {
      return;
    }
    $pet = new \HeroesofAbenez\Orm\Pet();
    $this->orm->pets->attach($pet);
    $pet->owner = $this->user->id;
    $pet->type = $petType;
    $this->orm->pets->persistAndFlush($pet);
  }
}
?>